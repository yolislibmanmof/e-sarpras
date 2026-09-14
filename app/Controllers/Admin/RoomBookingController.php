<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class RoomBookingController extends Controller
{
    public function index(): void
    {
        $status = Request::input('status', '');
        $page   = max(1, (int) Request::input('page', 1));
        $perPage = 10;

        $where  = $status !== '' ? 'WHERE rb.status = ?' : '';
        $params = $status !== '' ? [$status] : [];

        $total = (int) ($this->db()->selectOne(
            "SELECT COUNT(*) AS total FROM room_bookings rb {$where}",
            $params
        )['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT rb.*, r.name AS room_name
             FROM room_bookings rb
             LEFT JOIN rooms r ON r.id = rb.room_id
             {$where}
             ORDER BY rb.id DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $base = admin_url('/peminjaman-ruangan') . ($status !== '' ? '?status=' . urlencode($status) : '');

        $this->adminView('admin/pages/room_bookings/index', [
            'title'      => 'Peminjaman Ruangan',
            'rows'       => $rows,
            'status'     => $status,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function show(string $id): void
    {
        $booking = $this->db()->selectOne(
            "SELECT rb.*, r.name AS room_name, r.capacity AS room_capacity
             FROM room_bookings rb
             LEFT JOIN rooms r ON r.id = rb.room_id
             WHERE rb.id = ?",
            [$id]
        );

        if ($booking === null) {
            Session::flash('error', 'Peminjaman ruangan tidak ditemukan.');
            $this->redirect(admin_url('/peminjaman-ruangan'));
        }

        $this->adminView('admin/pages/room_bookings/show', [
            'title'   => 'Detail Peminjaman Ruangan',
            'booking' => $booking,
        ]);
    }

    public function verify(string $id): void
    {
        $this->find($id);
        $this->db()->update('room_bookings', ['status' => 'Diverifikasi'], ['id' => $id]);
        AuditLogger::log('room_booking.verify', 'room_booking', $id);
        Session::flash('success', 'Peminjaman ruangan telah diverifikasi.');
        $this->redirect(admin_url('/peminjaman-ruangan/' . $id));
    }

    public function approve(string $id): void
    {
        $booking  = $this->find($id);
        $decision = Request::input('decision', '');

        if (!in_array($decision, ['Disetujui', 'Ditolak'], true)) {
            Session::flash('error', 'Keputusan tidak valid.');
            $this->redirect(admin_url('/peminjaman-ruangan/' . $id));
        }

        $update = [
            'approval_status' => $decision,
            'approved_by'     => auth_id(),
            'approved_at'     => date('Y-m-d H:i:s'),
        ];

        if ($decision === 'Disetujui') {
            $update['status'] = 'Disetujui';
            $this->db()->insert('utilization_logs', [
                'room_id'           => (int) $booking['room_id'],
                'activity_name'     => $booking['activity_name'],
                'start_at'          => $booking['start_at'],
                'end_at'            => $booking['end_at'],
                'participant_count' => (int) $booking['participant_count'],
                'source_type'       => 'room_booking',
                'source_id'         => (int) $id,
                'created_by'        => auth_id(),
            ]);
        } else {
            $update['status'] = 'Ditolak';
        }

        $this->db()->update('room_bookings', $update, ['id' => $id]);
        AuditLogger::log('room_booking.' . strtolower($decision), 'room_booking', $id);
        Session::flash('success', 'Keputusan persetujuan telah disimpan.');
        $this->redirect(admin_url('/peminjaman-ruangan/' . $id));
    }

    private function find(string $id): array
    {
        $booking = $this->db()->selectOne('SELECT * FROM room_bookings WHERE id = ?', [$id]);
        if ($booking === null) {
            Session::flash('error', 'Peminjaman ruangan tidak ditemukan.');
            $this->redirect(admin_url('/peminjaman-ruangan'));
        }
        return $booking;
    }
}