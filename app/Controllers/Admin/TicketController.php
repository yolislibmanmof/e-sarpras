<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class TicketController extends Controller
{
    public function index(): void
    {
        $q        = Request::input('q', '');
        $status   = Request::input('status', '');
        $priority = Request::input('priority', '');
        $page     = max(1, (int) Request::input('page', 1));
        $perPage  = 10;

        $clauses = [];
        $params  = [];
        if ($q !== '') { $clauses[] = '(t.ticket_code LIKE ? OR t.title LIKE ?)'; $params[] = "%{$q}%"; $params[] = "%{$q}%"; }
        if ($status !== '') { $clauses[] = 't.status = ?'; $params[] = $status; }
        if ($priority !== '') { $clauses[] = 't.priority = ?'; $params[] = $priority; }
        $where = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM tickets t {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT t.*, b.name AS building_name, r.name AS room_name,
                    tech.name AS technician_name, v.name AS vendor_name
             FROM tickets t
             LEFT JOIN buildings b ON b.id = t.building_id
             LEFT JOIN rooms r ON r.id = t.room_id
             LEFT JOIN technicians tech ON tech.id = t.assigned_technician_id
             LEFT JOIN vendors v ON v.id = t.assigned_vendor_id
             {$where}
             ORDER BY t.id DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $query = http_build_query(array_filter(['q' => $q, 'status' => $status, 'priority' => $priority], static fn ($v) => $v !== ''));
        $base  = admin_url('/tiket') . ($query !== '' ? '?' . $query : '');

        $this->adminView('admin/pages/tickets/index', [
            'title'      => 'Tiket Kerusakan',
            'rows'       => $rows,
            'q'          => $q,
            'status'     => $status,
            'priority'   => $priority,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function show(string $id): void
    {
        $ticket = $this->db()->selectOne(
            "SELECT t.*, b.name AS building_name, r.name AS room_name, a.name AS asset_name,
                    tech.name AS technician_name, v.name AS vendor_name
             FROM tickets t
             LEFT JOIN buildings b ON b.id = t.building_id
             LEFT JOIN rooms r ON r.id = t.room_id
             LEFT JOIN assets a ON a.id = t.asset_id
             LEFT JOIN technicians tech ON tech.id = t.assigned_technician_id
             LEFT JOIN vendors v ON v.id = t.assigned_vendor_id
             WHERE t.id = ?",
            [$id]
        );

        if ($ticket === null) {
            Session::flash('error', 'Tiket tidak ditemukan.');
            $this->redirect(admin_url('/tiket'));
        }

        $this->adminView('admin/pages/tickets/show', [
            'title'       => 'Detail Tiket',
            'ticket'      => $ticket,
            'attachments' => $this->db()->select('SELECT * FROM ticket_attachments WHERE ticket_id = ? ORDER BY id ASC', [$id]),
            'histories'   => $this->db()->select('SELECT * FROM ticket_histories WHERE ticket_id = ? ORDER BY id DESC', [$id]),
            'technicians' => $this->db()->select("SELECT * FROM technicians WHERE is_active = 1 ORDER BY name"),
            'vendors'     => $this->db()->select('SELECT * FROM vendors ORDER BY name'),
        ]);
    }

    public function file(string $id): void
    {
        $attachment = $this->db()->selectOne('SELECT * FROM ticket_attachments WHERE id = ?', [$id]);
        if ($attachment === null) {
            $this->redirect(admin_url('/tiket'));
        }

        $path = rtrim(config('upload.base_path'), '/') . '/' . $attachment['file_path'];
        if (!is_file($path)) {
            Session::flash('error', 'File tidak ditemukan di penyimpanan.');
            $this->redirect(admin_url('/tiket/' . $attachment['ticket_id']));
        }

        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp',
            'pdf' => 'application/pdf',
        ][$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($path));
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }

    public function verify(string $id): void
    {
        $ticket = $this->findTicket($id);
        $this->changeStatus($ticket, 'Diverifikasi', Request::input('note', '') ?: 'Laporan terverifikasi oleh Sarpras.');
        Session::flash('success', 'Tiket telah diverifikasi.');
        $this->redirect(admin_url('/tiket/' . $id));
    }

    public function reject(string $id): void
    {
        $ticket = $this->findTicket($id);
        $note = Request::input('note', '');
        if (trim($note) === '') {
            Session::flash('error', 'Alasan penolakan wajib diisi.');
            $this->redirect(admin_url('/tiket/' . $id));
        }
        $this->changeStatus($ticket, 'Ditolak', $note);
        Session::flash('success', 'Tiket telah ditolak.');
        $this->redirect(admin_url('/tiket/' . $id));
    }

    public function assign(string $id): void
    {
        $ticket = $this->findTicket($id);

        $type = Request::input('assignee_type', '');
        $aid  = (int) Request::input('assignee_id', 0);

        $update = [];
        $assigneeName = '';

        if ($type === 'technician') {
            $tech = $this->db()->selectOne('SELECT * FROM technicians WHERE id = ? AND is_active = 1', [$aid]);
            if ($tech === null) {
                Session::flash('error', 'Teknisi tidak valid.');
                $this->redirect(admin_url('/tiket/' . $id));
            }
            $update['assigned_technician_id'] = $aid;
            $update['assigned_vendor_id'] = null;
            $assigneeName = $tech['name'];
        } elseif ($type === 'vendor') {
            $vendor = $this->db()->selectOne('SELECT * FROM vendors WHERE id = ?', [$aid]);
            if ($vendor === null) {
                Session::flash('error', 'Vendor tidak valid.');
                $this->redirect(admin_url('/tiket/' . $id));
            }
            $update['assigned_vendor_id'] = $aid;
            $update['assigned_technician_id'] = null;
            $assigneeName = $vendor['name'];
        } else {
            Session::flash('error', 'Pilih jenis penugasan terlebih dahulu.');
            $this->redirect(admin_url('/tiket/' . $id));
        }

        $priority = Request::input('priority', '');
        if (in_array($priority, ['Normal', 'Mendesak', 'Darurat'], true)) {
            $update['priority'] = $priority;
        }

        $estimated = Request::input('estimated_completion', '');
        if ($estimated !== '') {
            $update['estimated_completion'] = $estimated;
        }

        $update['status'] = 'Sedang Diperbaiki';
        $update['updated_by'] = auth_id();

        $this->db()->update('tickets', $update, ['id' => $id]);
        $this->db()->insert('ticket_histories', [
            'ticket_id'  => (int) $id,
            'user_id'    => auth_id(),
            'old_status' => $ticket['status'],
            'new_status' => 'Sedang Diperbaiki',
            'note'       => 'Ditugaskan kepada ' . $assigneeName . '.',
        ]);

        AuditLogger::log('ticket.assign', 'ticket', $id, null, ['assignee' => $assigneeName]);
        Session::flash('success', 'Penugasan berhasil disimpan.');
        $this->redirect(admin_url('/tiket/' . $id));
    }

    public function updateStatus(string $id): void
    {
        $ticket = $this->findTicket($id);
        $newStatus = Request::input('new_status', '');

        if (!in_array($newStatus, ['Sedang Diperbaiki', 'Menunggu Sparepart', 'Selesai'], true)) {
            Session::flash('error', 'Status tidak valid.');
            $this->redirect(admin_url('/tiket/' . $id));
        }

        $note = Request::input('note', '') ?: ('Status diubah menjadi ' . $newStatus . '.');
        $this->changeStatus($ticket, $newStatus, $note);

        Session::flash('success', 'Status tiket diperbarui.');
        $this->redirect(admin_url('/tiket/' . $id));
    }

    private function findTicket(string $id): array
    {
        $ticket = $this->db()->selectOne('SELECT * FROM tickets WHERE id = ?', [$id]);
        if ($ticket === null) {
            Session::flash('error', 'Tiket tidak ditemukan.');
            $this->redirect(admin_url('/tiket'));
        }
        return $ticket;
    }

    private function changeStatus(array $ticket, string $newStatus, string $note): void
    {
        $update = [
            'status'     => $newStatus,
            'updated_by' => auth_id(),
        ];
        if ($newStatus === 'Selesai') {
            $update['completed_at'] = date('Y-m-d H:i:s');
        }

        $this->db()->update('tickets', $update, ['id' => $ticket['id']]);
        $this->db()->insert('ticket_histories', [
            'ticket_id'  => (int) $ticket['id'],
            'user_id'    => auth_id(),
            'old_status' => $ticket['status'],
            'new_status' => $newStatus,
            'note'       => $note,
        ]);

        AuditLogger::log('ticket.status', 'ticket', $ticket['id'], ['status' => $ticket['status']], ['status' => $newStatus]);
    }
}