<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class StockCategoryController extends Controller
{
    public function index(): void
    {
        $this->adminView('admin/pages/stock_categories/index', [
            'title' => 'Kategori Stok',
            'rows'  => $this->db()->select(
                "SELECT c.*, (SELECT COUNT(*) FROM stocks s WHERE s.stock_category_id = c.id) AS stock_count
                 FROM stock_categories c ORDER BY c.name"
            ),
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['code' => 'required|max:100', 'name' => 'required|max:191']);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM stock_categories WHERE code = ? OR name = ?', [$data['code'], $data['name']]) !== null) {
            $errors = ['code' => ['Kode atau nama kategori sudah digunakan.']];
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            $this->redirect(admin_url('/stok-kategori'));
        }

        $id = $this->db()->insert('stock_categories', [
            'code'        => $data['code'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        AuditLogger::log('stock_category.create', 'stock', $id);
        Session::flash('success', 'Kategori stok berhasil disimpan.');
        $this->redirect(admin_url('/stok-kategori'));
    }

    public function destroy(string $id): void
    {
        if ($this->db()->count('stocks', ['stock_category_id' => $id]) > 0) {
            Session::flash('error', 'Kategori tidak dapat dihapus karena masih memiliki stok.');
            $this->redirect(admin_url('/stok-kategori'));
        }

        $this->db()->delete('stock_categories', ['id' => $id]);
        AuditLogger::log('stock_category.delete', 'stock', $id);
        Session::flash('success', 'Kategori stok berhasil dihapus.');
        $this->redirect(admin_url('/stok-kategori'));
    }
}