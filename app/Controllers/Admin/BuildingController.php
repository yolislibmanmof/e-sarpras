<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class BuildingController extends Controller
{
    public function index(): void
    {
        $q       = Request::input('q', '');
        $page    = max(1, (int) Request::input('page', 1));
        $perPage = 10;

        $where  = '';
        $params = [];
        if ($q !== '') {
            $where  = 'WHERE name LIKE ? OR code LIKE ?';
            $params = ["%{$q}%", "%{$q}%"];
        }

        $total  = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM buildings {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select("SELECT * FROM buildings {$where} ORDER BY name ASC LIMIT {$perPage} OFFSET {$offset}", $params);
        $base = admin_url('/gedung') . ($q !== '' ? '?q=' . urlencode($q) : '');

        $this->adminView('admin/pages/buildings/index', [
            'title'      => 'Gedung',
            'rows'       => $rows,
            'q'          => $q,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/pages/buildings/form', ['title' => 'Tambah Gedung', 'building' => null, 'floors' => []]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['code' => 'required|max:100', 'name' => 'required|max:191']);
        if ($errors === [] && $this->db()->selectOne('SELECT id FROM buildings WHERE code = ?', [$data['code']]) !== null) {
            $errors = ['code' => ['Kode gedung sudah digunakan.']];
        }

        $photo = $this->handlePhoto(null);
        if ($photo['error'] !== null) { Session::flash('error', $photo['error']); Session::flash('old', $data); $this->redirect(admin_url('/gedung/tambah')); }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/gedung/tambah'));
        }

        $id = $this->db()->insert('buildings', $this->mapData($data) + ['photo' => $photo['path']]);

        AuditLogger::log('building.create', 'building', $id, null, ['code' => $data['code']]);
        Session::flash('success', 'Gedung berhasil disimpan.');
        $this->redirect(admin_url('/gedung'));
    }

    public function edit(string $id): void
    {
        $building = $this->db()->selectOne('SELECT * FROM buildings WHERE id = ?', [$id]);
        if ($building === null) {
            Session::flash('error', 'Gedung tidak ditemukan.');
            $this->redirect(admin_url('/gedung'));
        }

        $floors = $this->db()->select('SELECT * FROM floors WHERE building_id = ? ORDER BY level ASC', [$id]);

        $this->adminView('admin/pages/buildings/form', ['title' => 'Ubah Gedung', 'building' => $building, 'floors' => $floors]);
    }

    public function update(string $id): void
    {
        $building = $this->db()->selectOne('SELECT * FROM buildings WHERE id = ?', [$id]);
        if ($building === null) {
            Session::flash('error', 'Gedung tidak ditemukan.');
            $this->redirect(admin_url('/gedung'));
        }

        $data = Request::all();
        $errors = $this->validate($data, ['code' => 'required|max:100', 'name' => 'required|max:191']);
        if ($errors === [] && $this->db()->selectOne('SELECT id FROM buildings WHERE code = ? AND id <> ?', [$data['code'], $id]) !== null) {
            $errors = ['code' => ['Kode gedung sudah digunakan.']];
        }

        $photo = $this->handlePhoto($building['photo'] ?? null);
        if ($photo['error'] !== null) { Session::flash('error', $photo['error']); Session::flash('old', $data); $this->redirect(admin_url('/gedung/' . $id . '/ubah')); }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/gedung/' . $id . '/ubah'));
        }

        $this->db()->update('buildings', $this->mapData($data) + ['photo' => $photo['path']], ['id' => $id]);

        AuditLogger::log('building.update', 'building', $id, $building, $data);
        Session::flash('success', 'Gedung berhasil diperbarui.');
        $this->redirect(admin_url('/gedung'));
    }

    public function destroy(string $id): void
    {
        $building = $this->db()->selectOne('SELECT * FROM buildings WHERE id = ?', [$id]);
        if ($building === null) {
            Session::flash('error', 'Gedung tidak ditemukan.');
            $this->redirect(admin_url('/gedung'));
        }

        $floors = $this->db()->count('floors', ['building_id' => $id]);
        $rooms  = $this->db()->count('rooms', ['building_id' => $id]);
        $assets = $this->db()->count('assets', ['building_id' => $id]);

        if ($floors + $rooms + $assets > 0) {
            Session::flash('error', 'Gedung tidak dapat dihapus karena masih memiliki lantai, ruangan, atau aset.');
            $this->redirect(admin_url('/gedung'));
        }

        $this->db()->delete('buildings', ['id' => $id]);
        AuditLogger::log('building.delete', 'building', $id, $building, null);
        Session::flash('success', 'Gedung berhasil dihapus.');
        $this->redirect(admin_url('/gedung'));
    }

    /**
     * Unggah foto gedung.
     * @return array{error: ?string, path: ?string}
     */
    private function handlePhoto(?string $existing): array
    {
        $file = Request::file('photo');
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['error' => null, 'path' => $existing];
        }
        $result = upload_file($file, 'buildings');
        if ($result['ok']) {
            return ['error' => null, 'path' => $result['path']];
        }
        return ['error' => $result['error'], 'path' => $existing];
    }

    private function mapData(array $data): array
    {
        return [
            'code'                   => $data['code'],
            'name'                   => $data['name'],
            'address'                => $data['address'] ?? null,
            'ownership_status'       => $data['ownership_status'] ?? null,
            'land_area'              => ($data['land_area'] ?? '') === '' ? null : (float) $data['land_area'],
            'building_area'          => ($data['building_area'] ?? '') === '' ? null : (float) $data['building_area'],
            'year_built'             => ($data['year_built'] ?? '') === '' ? null : (float) $data['year_built'],
            'condition'              => $data['condition'] ?? 'Baik',
            'is_disability_friendly' => isset($data['is_disability_friendly']) ? 1 : 0,
            'has_ramp'               => isset($data['has_ramp']) ? 1 : 0,
            'has_disability_toilet'  => isset($data['has_disability_toilet']) ? 1 : 0,
            'has_lift'               => isset($data['has_lift']) ? 1 : 0,
            'has_guide_path'         => isset($data['has_guide_path']) ? 1 : 0,
            'notes'                  => $data['notes'] ?? null,
        ];
    }
}