<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class RoomController extends Controller
{
    public function index(): void
    {
        $q        = Request::input('q', '');
        $building = Request::input('building', '');
        $page     = max(1, (int) Request::input('page', 1));
        $perPage  = 10;

        $clauses  = [];
        $params   = [];
        if ($q !== '') {
            $clauses[] = '(r.name LIKE ? OR r.code LIKE ?)';
            $params[] = "%{$q}%";
            $params[] = "%{$q}%";
        }
        if ($building !== '') {
            $clauses[] = 'r.building_id = ?';
            $params[] = $building;
        }
        $where = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM rooms r {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT r.*, b.name AS building_name
             FROM rooms r
             LEFT JOIN buildings b ON b.id = r.building_id
             {$where}
             ORDER BY r.code ASC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $query = http_build_query(array_filter(['q' => $q, 'building' => $building], static fn ($v) => $v !== ''));
        $base  = admin_url('/ruangan') . ($query !== '' ? '?' . $query : '');

        $this->adminView('admin/pages/rooms/index', [
            'title'      => 'Ruangan',
            'rows'       => $rows,
            'q'          => $q,
            'building'   => $building,
            'buildings'  => $this->db()->select('SELECT id, name FROM buildings ORDER BY name ASC'),
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/pages/rooms/form', [
            'title'  => 'Tambah Ruangan',
            'room'   => null,
            'floors' => $this->groupedFloors(),
        ] + $this->formBase());
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'code'        => 'required|max:100',
            'name'        => 'required|max:191',
            'building_id' => 'required|numeric',
            'capacity'    => 'numeric',
        ]);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM rooms WHERE code = ?', [$data['code']]) !== null) {
            $errors = ['code' => ['Kode ruangan sudah digunakan.']];
        }

        $photo = $this->handlePhoto(null);
        if ($photo['error'] !== null) { Session::flash('error', $photo['error']); Session::flash('old', $data); $this->redirect(admin_url('/ruangan/tambah')); }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/ruangan/tambah'));
        }

        $id = $this->db()->insert('rooms', $this->mapData($data) + ['photo' => $photo['path']]);

        AuditLogger::log('room.create', 'room', $id, null, ['code' => $data['code']]);
        Session::flash('success', 'Ruangan berhasil disimpan.');
        $this->redirect(admin_url('/ruangan'));
    }

    public function edit(string $id): void
    {
        $room = $this->db()->selectOne('SELECT * FROM rooms WHERE id = ?', [$id]);
        if ($room === null) {
            Session::flash('error', 'Ruangan tidak ditemukan.');
            $this->redirect(admin_url('/ruangan'));
        }

        $this->adminView('admin/pages/rooms/form', [
            'title'  => 'Ubah Ruangan',
            'room'   => $room,
            'floors' => $this->groupedFloors(),
        ] + $this->formBase());
    }

    public function update(string $id): void
    {
        $room = $this->db()->selectOne('SELECT * FROM rooms WHERE id = ?', [$id]);
        if ($room === null) {
            Session::flash('error', 'Ruangan tidak ditemukan.');
            $this->redirect(admin_url('/ruangan'));
        }

        $data = Request::all();
        $errors = $this->validate($data, [
            'code'        => 'required|max:100',
            'name'        => 'required|max:191',
            'building_id' => 'required|numeric',
            'capacity'    => 'numeric',
        ]);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM rooms WHERE code = ? AND id <> ?', [$data['code'], $id]) !== null) {
            $errors = ['code' => ['Kode ruangan sudah digunakan.']];
        }

        $photo = $this->handlePhoto($room['photo'] ?? null);
        if ($photo['error'] !== null) { Session::flash('error', $photo['error']); Session::flash('old', $data); $this->redirect(admin_url('/ruangan/' . $id . '/ubah')); }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/ruangan/' . $id . '/ubah'));
        }

        $this->db()->update('rooms', $this->mapData($data) + ['photo' => $photo['path']], ['id' => $id]);

        AuditLogger::log('room.update', 'room', $id, $room, $data);
        Session::flash('success', 'Ruangan berhasil diperbarui.');
        $this->redirect(admin_url('/ruangan'));
    }

    public function destroy(string $id): void
    {
        $room = $this->db()->selectOne('SELECT * FROM rooms WHERE id = ?', [$id]);
        if ($room === null) {
            Session::flash('error', 'Ruangan tidak ditemukan.');
            $this->redirect(admin_url('/ruangan'));
        }

        $assets   = $this->db()->count('assets', ['room_id' => $id]);
        $bookings = $this->db()->count('room_bookings', ['room_id' => $id]);

        if ($assets + $bookings > 0) {
            Session::flash('error', 'Ruangan tidak dapat dihapus karena masih memiliki aset atau jadwal peminjaman.');
            $this->redirect(admin_url('/ruangan'));
        }

        $this->db()->delete('rooms', ['id' => $id]);
        AuditLogger::log('room.delete', 'room', $id, $room, null);
        Session::flash('success', 'Ruangan berhasil dihapus.');
        $this->redirect(admin_url('/ruangan'));
    }

    /**
     * Unggah foto ruangan.
     * @return array{error: ?string, path: ?string}
     */
    private function handlePhoto(?string $existing): array
    {
        $file = Request::file('photo');
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['error' => null, 'path' => $existing];
        }
        $result = upload_file($file, 'rooms');
        if ($result['ok']) {
            return ['error' => null, 'path' => $result['path']];
        }
        return ['error' => $result['error'], 'path' => $existing];
    }

    private function formBase(): array
    {
        return [
            'buildings' => $this->db()->select('SELECT id, name FROM buildings ORDER BY name ASC'),
        ];
    }

    private function groupedFloors(): array
    {
        $floors = $this->db()->select('SELECT * FROM floors ORDER BY building_id ASC, level ASC');
        $grouped = [];
        foreach ($floors as $floor) {
            $grouped[(int) $floor['building_id']][] = $floor;
        }
        return $grouped;
    }

    private function mapData(array $data): array
    {
        return [
            'building_id'            => (int) $data['building_id'],
            'floor_id'               => ($data['floor_id'] ?? '') === '' ? null : (int) $data['floor_id'],
            'code'                   => $data['code'],
            'name'                   => $data['name'],
            'room_type'              => $data['room_type'] ?? 'Ruang Kelas',
            'capacity'               => (int) ($data['capacity'] ?? 0),
            'area'                   => ($data['area'] ?? '') === '' ? null : (float) $data['area'],
            'condition'              => $data['condition'] ?? 'Baik',
            'status'                 => $data['status'] ?? 'Aktif',
            'facilities'             => $data['facilities'] ?? null,
            'is_disability_friendly' => isset($data['is_disability_friendly']) ? 1 : 0,
            'notes'                  => $data['notes'] ?? null,
        ];
    }
}