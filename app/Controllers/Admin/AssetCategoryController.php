<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class AssetCategoryController extends Controller
{
    public function index(): void
    {
        $rows = $this->db()->select(
            "SELECT c.*, (SELECT COUNT(*) FROM assets a WHERE a.asset_category_id = c.id) AS asset_count
             FROM asset_categories c
             ORDER BY c.name ASC"
        );

        $this->adminView('admin/pages/asset_categories/index', [
            'title' => 'Kategori Aset',
            'rows'  => $rows,
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'code' => 'required|max:100',
            'name' => 'required|max:191',
        ]);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM asset_categories WHERE code = ? OR name = ?', [$data['code'], $data['name']]) !== null) {
            $errors = ['code' => ['Kode atau nama kategori sudah digunakan.']];
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            $this->redirect(admin_url('/aset-kategori'));
        }

        $id = $this->db()->insert('asset_categories', [
            'code'        => $data['code'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        AuditLogger::log('asset_category.create', 'asset', $id, null, ['code' => $data['code']]);
        Session::flash('success', 'Kategori aset berhasil disimpan.');
        $this->redirect(admin_url('/aset-kategori'));
    }

    public function destroy(string $id): void
    {
        $count = $this->db()->count('assets', ['asset_category_id' => $id]);
        if ($count > 0) {
            Session::flash('error', 'Kategori tidak dapat dihapus karena masih memiliki aset.');
            $this->redirect(admin_url('/aset-kategori'));
        }

        $this->db()->delete('asset_categories', ['id' => $id]);
        AuditLogger::log('asset_category.delete', 'asset', $id);
        Session::flash('success', 'Kategori aset berhasil dihapus.');
        $this->redirect(admin_url('/aset-kategori'));
    }
}