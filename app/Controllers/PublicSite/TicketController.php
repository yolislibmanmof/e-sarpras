<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class TicketController extends Controller
{
    private const REPORTER_TYPES = ['Mahasiswa', 'Dosen', 'Tendik', 'Tamu'];
    private const CATEGORIES = ['Listrik', 'AC & Pendingin', 'Plumbing & Air', 'Mebel', 'Jaringan & Internet', 'Bangunan', 'Kebersihan', 'Lainnya'];
    private const PRIORITIES = ['Normal', 'Mendesak', 'Darurat'];

    public function create(): void
    {
        $db = $this->db();
        $this->view('public/pages/ticket_create', [
            'title'         => 'Lapor Kerusakan',
            'categories'    => self::CATEGORIES,
            'priorities'    => self::PRIORITIES,
            'reporterTypes' => self::REPORTER_TYPES,
            'buildings'     => $db->select('SELECT id, name FROM buildings ORDER BY name'),
            'groupedRooms'  => $this->grouped('rooms'),
            'groupedAssets' => $this->grouped('assets'),
            'preCategory'   => Request::input('kategori', ''),
            'stats'         => [
                'selesai'  => $db->count('tickets', ['status' => 'Selesai']),
                'menunggu' => $db->count('tickets', ['status' => 'Menunggu Verifikasi']),
                'proses'   => $db->count('tickets', ['status' => 'Diverifikasi']) + $db->count('tickets', ['status' => 'Sedang Diperbaiki']) + $db->count('tickets', ['status' => 'Menunggu Sparepart']),
            ],
        ], 'public/layouts/main');
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'reporter_name'    => 'required|max:191',
            'reporter_contact' => 'required|max:100',
            'building_id'      => 'required|numeric',
            'category'         => 'required',
            'title'            => 'required|max:255',
            'description'      => 'required|min:10',
        ]);

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(base_url('/lapor-kerusakan'));
        }

        $attachmentPath = null;
        $file = Request::file('photo');
        if ($file !== null && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = upload_file($file, 'tickets');
            if (!$result['ok']) {
                Session::flash('error', $result['error']);
                Session::flash('old', $data);
                $this->redirect(base_url('/lapor-kerusakan'));
            }
            $attachmentPath = $result['path'];
        }

        $code = $this->nextCode();
        $ticketId = $this->db()->insert('tickets', [
            'ticket_code'      => $code,
            'reporter_name'    => $data['reporter_name'],
            'reporter_type'    => in_array($data['reporter_type'] ?? '', self::REPORTER_TYPES, true) ? $data['reporter_type'] : 'Mahasiswa',
            'reporter_contact' => $data['reporter_contact'],
            'building_id'      => (int) $data['building_id'],
            'room_id'          => ($data['room_id'] ?? '') === '' ? null : (int) $data['room_id'],
            'asset_id'         => ($data['asset_id'] ?? '') === '' ? null : (int) $data['asset_id'],
            'category'         => $data['category'],
            'priority'         => in_array($data['priority'] ?? '', self::PRIORITIES, true) ? $data['priority'] : 'Normal',
            'status'           => 'Menunggu Verifikasi',
            'title'            => $data['title'],
            'description'      => $data['description'],
            'incident_date'    => ($data['incident_date'] ?? '') === '' ? null : $data['incident_date'],
        ]);

        if ($attachmentPath !== null) {
            $this->db()->insert('ticket_attachments', [
                'ticket_id' => $ticketId,
                'file_path' => $attachmentPath,
                'caption'   => 'Foto bukti pelapor',
            ]);
        }

        $this->db()->insert('ticket_histories', [
            'ticket_id'  => $ticketId,
            'user_id'    => null,
            'old_status' => null,
            'new_status' => 'Menunggu Verifikasi',
            'note'       => 'Laporan diterima dari pelapor.',
        ]);

        AuditLogger::log('ticket.create', 'ticket', $ticketId, null, ['ticket_code' => $code]);
        Session::flash('success', 'Laporan Anda telah diterima. Kode tiket: ' . $code);
        $this->redirect(base_url('/lacak-laporan?code=' . urlencode($code)));
    }

    public function track(): void
    {
        $code   = Request::input('code', '');
        $ticket = null;
        $histories = [];

        if ($code !== '') {
            $ticket = $this->db()->selectOne(
                "SELECT t.*, b.name AS building_name, r.name AS room_name
                 FROM tickets t
                 LEFT JOIN buildings b ON b.id = t.building_id
                 LEFT JOIN rooms r ON r.id = t.room_id
                 WHERE t.ticket_code = ?",
                [$code]
            );
            if ($ticket !== null) {
                $histories = $this->db()->select('SELECT * FROM ticket_histories WHERE ticket_id = ? ORDER BY id ASC', [$ticket['id']]);
            }
        }

        $this->view('public/pages/ticket_tracking', [
            'title'     => 'Lacak Laporan',
            'code'      => $code,
            'ticket'    => $ticket,
            'histories' => $histories,
        ], 'public/layouts/main');
    }

    private function grouped(string $table): array
    {
        $sql = $table === 'rooms'
            ? 'SELECT id, building_id, code, name FROM rooms ORDER BY building_id, code'
            : "SELECT id, building_id, code, name FROM assets WHERE status = 'Aktif' ORDER BY building_id, code";

        $rows = $this->db()->select($sql);
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[(int) $row['building_id']][] = $row;
        }
        return $grouped;
    }

    private function nextCode(): string
    {
        $count = $this->db()->count('tickets');
        do {
            $count++;
            $code = 'TKT-' . date('Ymd') . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        } while ($this->db()->selectOne('SELECT id FROM tickets WHERE ticket_code = ?', [$code]) !== null);
        return $code;
    }
}