<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class ItemRequestController extends Controller
{
    public function index(): void
    {
        $status = Request::input('status', '');
        $page   = max(1, (int) Request::input('page', 1));
        $perPage = 10;

        $where  = $status !== '' ? 'WHERE status = ?' : '';
        $params = $status !== '' ? [$status] : [];

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM item_requests {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT * FROM item_requests {$where} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $base = admin_url('/permintaan-barang') . ($status !== '' ? '?status=' . urlencode($status) : '');

        $this->adminView('admin/pages/item_requests/index', [
            'title'      => 'Permintaan Barang',
            'rows'       => $rows,
            'status'     => $status,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function show(string $id): void
    {
        $request = $this->db()->selectOne('SELECT * FROM item_requests WHERE id = ?', [$id]);
        if ($request === null) {
            Session::flash('error', 'Permintaan tidak ditemukan.');
            $this->redirect(admin_url('/permintaan-barang'));
        }

        $this->adminView('admin/pages/item_requests/show', [
            'title'   => 'Detail Permintaan',
            'request' => $request,
            'items'   => $this->db()->select(
                "SELECT iri.*, s.item_code AS stock_code, s.quantity AS stock_available
                 FROM item_request_items iri
                 LEFT JOIN stocks s ON s.id = iri.stock_id
                 WHERE iri.item_request_id = ? ORDER BY iri.id",
                [$id]
            ),
        ]);
    }

    public function verify(string $id): void
    {
        $this->find($id);
        $this->db()->update('item_requests', ['status' => 'Diverifikasi'], ['id' => $id]);
        AuditLogger::log('item_request.verify', 'item_request', $id);
        Session::flash('success', 'Permintaan telah diverifikasi.');
        $this->redirect(admin_url('/permintaan-barang/' . $id));
    }

    public function approve(string $id): void
    {
        $this->find($id);
        $decision = Request::input('decision', '');

        if (!in_array($decision, ['Disetujui', 'Ditolak'], true)) {
            Session::flash('error', 'Keputusan tidak valid.');
            $this->redirect(admin_url('/permintaan-barang/' . $id));
        }

        $update = [
            'approval_status' => $decision,
            'approved_by'     => auth_id(),
            'approved_at'     => date('Y-m-d H:i:s'),
        ];
        if ($decision === 'Ditolak') {
            $update['status'] = 'Ditolak';
        }

        $this->db()->update('item_requests', $update, ['id' => $id]);
        AuditLogger::log('item_request.' . strtolower($decision), 'item_request', $id);
        Session::flash('success', 'Keputusan persetujuan telah disimpan.');
        $this->redirect(admin_url('/permintaan-barang/' . $id));
    }

    public function fulfill(string $id): void
    {
        $request = $this->find($id);

        if ($request['approval_status'] !== 'Disetujui') {
            Session::flash('error', 'Permintaan belum disetujui.');
            $this->redirect(admin_url('/permintaan-barang/' . $id));
        }

        $items = $this->db()->select('SELECT * FROM item_request_items WHERE item_request_id = ?', [$id]);

        // Periksa kecukupan stok terlebih dahulu.
        foreach ($items as $item) {
            if ($item['stock_id'] === null) {
                continue;
            }
            $stock = $this->db()->selectOne('SELECT * FROM stocks WHERE id = ?', [$item['stock_id']]);
            if ($stock === null || (float) $stock['quantity'] < (float) $item['quantity']) {
                Session::flash('error', 'Stok tidak mencukupi untuk barang: ' . $item['item_name'] . '.');
                $this->redirect(admin_url('/permintaan-barang/' . $id));
            }
        }

        $this->db()->beginTransaction();
        try {
            foreach ($items as $item) {
                if ($item['stock_id'] !== null) {
                    $stock = $this->db()->selectOne('SELECT * FROM stocks WHERE id = ?', [$item['stock_id']]);
                    $newQty = (float) $stock['quantity'] - (float) $item['quantity'];
                    $this->db()->update('stocks', ['quantity' => $newQty], ['id' => $stock['id']]);
                    $this->db()->insert('stock_movements', [
                        'stock_id'       => (int) $stock['id'],
                        'movement_type'  => 'out',
                        'quantity'       => (float) $item['quantity'],
                        'movement_date'  => date('Y-m-d H:i:s'),
                        'reference_type' => 'item_request',
                        'reference_id'   => (int) $id,
                        'notes'          => 'Penyerahan permintaan ' . $request['request_code'],
                        'created_by'     => auth_id(),
                    ]);
                }
            }

            $this->db()->update('item_requests', ['status' => 'Diserahkan'], ['id' => $id]);
            $this->db()->commit();
        } catch (\Throwable $e) {
            $this->db()->rollBack();
            Session::flash('error', 'Gagal memproses penyerahan barang.');
            $this->redirect(admin_url('/permintaan-barang/' . $id));
        }

        AuditLogger::log('item_request.fulfill', 'item_request', $id);
        Session::flash('success', 'Barang telah diserahkan dan stok diperbarui.');
        $this->redirect(admin_url('/permintaan-barang/' . $id));
    }

    private function find(string $id): array
    {
        $request = $this->db()->selectOne('SELECT * FROM item_requests WHERE id = ?', [$id]);
        if ($request === null) {
            Session::flash('error', 'Permintaan tidak ditemukan.');
            $this->redirect(admin_url('/permintaan-barang'));
        }
        return $request;
    }
}