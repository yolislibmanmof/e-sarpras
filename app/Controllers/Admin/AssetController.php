<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class AssetController extends Controller
{
    public function index(): void
    {
        $q        = Request::input('q', '');
        $category = Request::input('category', '');
        $building = Request::input('building', '');
        $condition= Request::input('condition', '');
        $page     = max(1, (int) Request::input('page', 1));
        $perPage  = 10;

        $clauses = [];
        $params  = [];
        if ($q !== '') {
            $clauses[] = '(a.code LIKE ? OR a.name LIKE ? OR a.serial_number LIKE ?)';
            $params[] = "%{$q}%";
            $params[] = "%{$q}%";
            $params[] = "%{$q}%";
        }
        if ($category !== '') { $clauses[] = 'a.asset_category_id = ?'; $params[] = $category; }
        if ($building !== '') { $clauses[] = 'a.building_id = ?'; $params[] = $building; }
        if ($condition !== '') { $clauses[] = 'a.condition = ?'; $params[] = $condition; }
        $where = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        $total = (int) ($this->db()->selectOne(
            "SELECT COUNT(*) AS total FROM assets a {$where}",
            $params
        )['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT a.*, c.name AS category_name, b.name AS building_name, r.name AS room_name
             FROM assets a
             LEFT JOIN asset_categories c ON c.id = a.asset_category_id
             LEFT JOIN buildings b ON b.id = a.building_id
             LEFT JOIN rooms r ON r.id = a.room_id
             {$where}
             ORDER BY a.code ASC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $query = http_build_query(array_filter([
            'q' => $q, 'category' => $category, 'building' => $building, 'condition' => $condition,
        ], static fn ($v) => $v !== ''));
        $base = admin_url('/aset') . ($query !== '' ? '?' . $query : '');

        $this->adminView('admin/pages/assets/index', [
            'title'      => 'Aset',
            'rows'       => $rows,
            'q'          => $q,
            'category'   => $category,
            'building'   => $building,
            'condition'  => $condition,
            'categories' => $this->db()->select('SELECT id, name FROM asset_categories ORDER BY name'),
            'buildings'  => $this->db()->select('SELECT id, name FROM buildings ORDER BY name'),
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/pages/assets/form', [
            'title'        => 'Tambah Aset',
            'asset'        => null,
            'suggested'    => $this->nextCode(),
            'groupedRooms' => $this->groupedRooms(),
        ] + $this->formBase());
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'code'              => 'required|max:100',
            'name'              => 'required|max:191',
            'asset_category_id' => 'required|numeric',
            'acquisition_value' => 'numeric',
        ]);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM assets WHERE code = ?', [$data['code']]) !== null) {
            $errors = ['code' => ['Kode aset sudah digunakan.']];
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/aset/tambah'));
        }

        $id = $this->db()->insert('assets', $this->mapData($data));
        AuditLogger::log('asset.create', 'asset', $id, null, ['code' => $data['code']]);
        Session::flash('success', 'Aset berhasil disimpan.');
        $this->redirect(admin_url('/aset/' . $id));
    }

    public function show(string $id): void
    {
        $asset = $this->db()->selectOne(
            "SELECT a.*, c.name AS category_name, b.name AS building_name, r.name AS room_name
             FROM assets a
             LEFT JOIN asset_categories c ON c.id = a.asset_category_id
             LEFT JOIN buildings b ON b.id = a.building_id
             LEFT JOIN rooms r ON r.id = a.room_id
             WHERE a.id = ?",
            [$id]
        );

        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $this->adminView('admin/pages/assets/show', [
            'title'      => 'Detail Aset',
            'asset'      => $asset,
            'labels'     => $this->db()->select('SELECT * FROM asset_labels WHERE asset_id = ? ORDER BY id DESC', [$id]),
            'histories'  => $this->db()->select('SELECT * FROM asset_condition_histories WHERE asset_id = ? ORDER BY id DESC', [$id]),
            'disposals'  => $this->db()->select('SELECT * FROM asset_disposals WHERE asset_id = ? ORDER BY id DESC', [$id]),
        ]);
    }

    public function edit(string $id): void
    {
        $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$id]);
        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $this->adminView('admin/pages/assets/form', [
            'title'        => 'Ubah Aset',
            'asset'        => $asset,
            'suggested'    => $asset['code'],
            'groupedRooms' => $this->groupedRooms(),
        ] + $this->formBase());
    }

    public function update(string $id): void
    {
        $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$id]);
        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $data = Request::all();
        $errors = $this->validate($data, [
            'code'              => 'required|max:100',
            'name'              => 'required|max:191',
            'asset_category_id' => 'required|numeric',
            'acquisition_value' => 'numeric',
        ]);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM assets WHERE code = ? AND id <> ?', [$data['code'], $id]) !== null) {
            $errors = ['code' => ['Kode aset sudah digunakan.']];
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/aset/' . $id . '/ubah'));
        }

        $this->db()->update('assets', $this->mapData($data), ['id' => $id]);
        AuditLogger::log('asset.update', 'asset', $id, $asset, $data);
        Session::flash('success', 'Aset berhasil diperbarui.');
        $this->redirect(admin_url('/aset/' . $id));
    }

    public function destroy(string $id): void
    {
        $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$id]);
        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $tickets = $this->db()->count('tickets', ['asset_id' => $id]);
        $borrows = $this->db()->count('borrow_items', ['asset_id' => $id]);
        if ($tickets + $borrows > 0) {
            Session::flash('error', 'Aset tidak dapat dihapus karena masih terkait tiket atau peminjaman.');
            $this->redirect(admin_url('/aset/' . $id));
        }

        $this->db()->delete('asset_labels', ['asset_id' => $id]);
        $this->db()->delete('asset_condition_histories', ['asset_id' => $id]);
        $this->db()->delete('asset_disposals', ['asset_id' => $id]);
        $this->db()->delete('assets', ['id' => $id]);
        AuditLogger::log('asset.delete', 'asset', $id, $asset, null);
        Session::flash('success', 'Aset berhasil dihapus.');
        $this->redirect(admin_url('/aset'));
    }

    private function formBase(): array
    {
        return [
            'categories' => $this->db()->select('SELECT id, name FROM asset_categories ORDER BY name'),
            'buildings'  => $this->db()->select('SELECT id, name FROM buildings ORDER BY name'),
        ];
    }

    private function groupedRooms(): array
    {
        $rooms = $this->db()->select('SELECT id, building_id, code, name FROM rooms ORDER BY building_id, code');
        $grouped = [];
        foreach ($rooms as $room) {
            $grouped[(int) $room['building_id']][] = $room;
        }
        return $grouped;
    }

    private function nextCode(): string
    {
        $count = $this->db()->count('assets');
        do {
            $count++;
            $code = 'ASET-' . date('Y') . '-' . str_pad((string) $count, 5, '0', STR_PAD_LEFT);
        } while ($this->db()->selectOne('SELECT id FROM assets WHERE code = ?', [$code]) !== null);
        return $code;
    }

    private function mapData(array $data): array
    {
        return [
            'asset_category_id' => (int) $data['asset_category_id'],
            'building_id'       => ($data['building_id'] ?? '') === '' ? null : (int) $data['building_id'],
            'room_id'           => ($data['room_id'] ?? '') === '' ? null : (int) $data['room_id'],
            'code'              => $data['code'],
            'name'              => $data['name'],
            'brand'             => $data['brand'] ?? null,
            'model'             => $data['model'] ?? null,
            'serial_number'     => $data['serial_number'] ?? null,
            'acquisition_date'  => ($data['acquisition_date'] ?? '') === '' ? null : $data['acquisition_date'],
            'acquisition_value' => (float) ($data['acquisition_value'] ?? 0),
            'condition'         => $data['condition'] ?? 'Baik',
            'status'            => $data['status'] ?? 'Aktif',
            'ownership'         => $data['ownership'] ?? null,
            'warranty_expiry'   => ($data['warranty_expiry'] ?? '') === '' ? null : $data['warranty_expiry'],
            'is_borrowable'     => isset($data['is_borrowable']) ? 1 : 0,
            'notes'             => $data['notes'] ?? null,
        ];
    }
}