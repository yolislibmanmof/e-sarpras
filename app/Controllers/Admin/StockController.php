<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class StockController extends Controller
{
    public function index(): void
    {
        $q        = Request::input('q', '');
        $category = Request::input('category', '');
        $page     = max(1, (int) Request::input('page', 1));
        $perPage  = 10;

        $clauses = [];
        $params  = [];
        if ($q !== '') { $clauses[] = '(s.item_code LIKE ? OR s.item_name LIKE ?)'; $params[] = "%{$q}%"; $params[] = "%{$q}%"; }
        if ($category !== '') { $clauses[] = 's.stock_category_id = ?'; $params[] = $category; }
        $where = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM stocks s {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT s.*, c.name AS category_name
             FROM stocks s
             LEFT JOIN stock_categories c ON c.id = s.stock_category_id
             {$where}
             ORDER BY s.item_name ASC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $query = http_build_query(array_filter(['q' => $q, 'category' => $category], static fn ($v) => $v !== ''));
        $base  = admin_url('/stok') . ($query !== '' ? '?' . $query : '');

        $this->adminView('admin/pages/stocks/index', [
            'title'      => 'Stok Gudang',
            'rows'       => $rows,
            'q'          => $q,
            'category'   => $category,
            'categories' => $this->db()->select('SELECT * FROM stock_categories ORDER BY name'),
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/pages/stocks/form', [
            'title'      => 'Tambah Stok',
            'stock'      => null,
            'movements'  => [],
            'categories' => $this->db()->select('SELECT * FROM stock_categories ORDER BY name'),
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'item_code' => 'required|max:100',
            'item_name' => 'required|max:191',
            'stock_category_id' => 'required|numeric',
            'quantity'  => 'numeric',
            'minimum_quantity' => 'numeric',
        ]);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM stocks WHERE item_code = ?', [$data['item_code']]) !== null) {
            $errors = ['item_code' => ['Kode barang sudah digunakan.']];
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/stok/tambah'));
        }

        $id = $this->db()->insert('stocks', $this->mapData($data));

        if ((float) ($data['quantity'] ?? 0) > 0) {
            $this->db()->insert('stock_movements', [
                'stock_id'       => $id,
                'movement_type'  => 'in',
                'quantity'       => (float) $data['quantity'],
                'movement_date'  => date('Y-m-d H:i:s'),
                'reference_type' => 'initial',
                'notes'          => 'Stok awal',
                'created_by'     => auth_id(),
            ]);
        }

        AuditLogger::log('stock.create', 'stock', $id, null, ['item_code' => $data['item_code']]);
        Session::flash('success', 'Stok barang berhasil disimpan.');
        $this->redirect(admin_url('/stok'));
    }

    public function edit(string $id): void
    {
        $stock = $this->db()->selectOne('SELECT * FROM stocks WHERE id = ?', [$id]);
        if ($stock === null) {
            Session::flash('error', 'Stok tidak ditemukan.');
            $this->redirect(admin_url('/stok'));
        }

        $this->adminView('admin/pages/stocks/form', [
            'title'     => 'Ubah Stok',
            'stock'     => $stock,
            'movements' => $this->db()->select('SELECT * FROM stock_movements WHERE stock_id = ? ORDER BY id DESC LIMIT 20', [$id]),
            'categories'=> $this->db()->select('SELECT * FROM stock_categories ORDER BY name'),
        ]);
    }

    public function update(string $id): void
    {
        $stock = $this->db()->selectOne('SELECT * FROM stocks WHERE id = ?', [$id]);
        if ($stock === null) {
            Session::flash('error', 'Stok tidak ditemukan.');
            $this->redirect(admin_url('/stok'));
        }

        $data = Request::all();
        $errors = $this->validate($data, [
            'item_code' => 'required|max:100',
            'item_name' => 'required|max:191',
            'stock_category_id' => 'required|numeric',
            'quantity'  => 'numeric',
            'minimum_quantity' => 'numeric',
        ]);

        if ($errors === [] && $this->db()->selectOne('SELECT id FROM stocks WHERE item_code = ? AND id <> ?', [$data['item_code'], $id]) !== null) {
            $errors = ['item_code' => ['Kode barang sudah digunakan.']];
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/stok/' . $id . '/ubah'));
        }

        $this->db()->update('stocks', $this->mapData($data), ['id' => $id]);
        AuditLogger::log('stock.update', 'stock', $id, $stock, $data);
        Session::flash('success', 'Stok barang berhasil diperbarui.');
        $this->redirect(admin_url('/stok'));
    }

    public function destroy(string $id): void
    {
        $stock = $this->db()->selectOne('SELECT * FROM stocks WHERE id = ?', [$id]);
        if ($stock === null) {
            Session::flash('error', 'Stok tidak ditemukan.');
            $this->redirect(admin_url('/stok'));
        }

        $movements = $this->db()->count('stock_movements', ['stock_id' => $id]);
        $requested = $this->db()->count('item_request_items', ['stock_id' => $id]);
        if ($movements + $requested > 0) {
            Session::flash('error', 'Stok tidak dapat dihapus karena memiliki riwayat mutasi atau permintaan.');
            $this->redirect(admin_url('/stok'));
        }

        $this->db()->delete('stocks', ['id' => $id]);
        AuditLogger::log('stock.delete', 'stock', $id, $stock, null);
        Session::flash('success', 'Stok barang berhasil dihapus.');
        $this->redirect(admin_url('/stok'));
    }

    public function move(string $id): void
    {
        $stock = $this->db()->selectOne('SELECT * FROM stocks WHERE id = ?', [$id]);
        if ($stock === null) {
            Session::flash('error', 'Stok tidak ditemukan.');
            $this->redirect(admin_url('/stok'));
        }

        $type = Request::input('movement_type', '');
        $qty  = (float) Request::input('quantity', 0);

        if (!in_array($type, ['in', 'out'], true) || $qty <= 0) {
            Session::flash('error', 'Jenis mutasi atau jumlah tidak valid.');
            $this->redirect(admin_url('/stok/' . $id . '/ubah'));
        }

        if ($type === 'out' && (float) $stock['quantity'] < $qty) {
            Session::flash('error', 'Jumlah stok tidak mencukupi untuk mutasi keluar.');
            $this->redirect(admin_url('/stok/' . $id . '/ubah'));
        }

        $newQuantity = $type === 'in' ? (float) $stock['quantity'] + $qty : (float) $stock['quantity'] - $qty;

        $this->db()->update('stocks', ['quantity' => $newQuantity], ['id' => $id]);
        $this->db()->insert('stock_movements', [
            'stock_id'       => (int) $id,
            'movement_type'  => $type,
            'quantity'       => $qty,
            'movement_date'  => date('Y-m-d H:i:s'),
            'reference_type' => 'manual',
            'notes'          => Request::input('notes', '') ?: ('Mutasi ' . ($type === 'in' ? 'masuk' : 'keluar')),
            'created_by'     => auth_id(),
        ]);

        AuditLogger::log('stock.movement', 'stock', $id, ['quantity' => $stock['quantity']], ['quantity' => $newQuantity]);
        Session::flash('success', 'Mutasi stok berhasil dicatat.');
        $this->redirect(admin_url('/stok/' . $id . '/ubah'));
    }

    private function mapData(array $data): array
    {
        return [
            'stock_category_id' => (int) $data['stock_category_id'],
            'item_code'         => $data['item_code'],
            'item_name'         => $data['item_name'],
            'specification'     => $data['specification'] ?? null,
            'unit'              => $data['unit'] ?? null,
            'quantity'          => (float) ($data['quantity'] ?? 0),
            'minimum_quantity'  => (float) ($data['minimum_quantity'] ?? 0),
            'location'          => $data['location'] ?? null,
            'condition'         => $data['condition'] ?? 'Baik',
            'is_borrowable'     => isset($data['is_borrowable']) ? 1 : 0,
            'notes'             => $data['notes'] ?? null,
        ];
    }
}