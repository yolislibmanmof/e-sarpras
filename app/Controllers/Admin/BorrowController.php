<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class BorrowController extends Controller
{
    public function index(): void
    {
        $status = Request::input('status', '');
        $q      = Request::input('q', '');
        $page   = max(1, (int) Request::input('page', 1));
        $perPage = 10;

        $clauses = [];
        $params  = [];
        if ($status !== '') { $clauses[] = 'status = ?'; $params[] = $status; }
        if ($q !== '') { $clauses[] = '(borrow_code LIKE ? OR borrower_name LIKE ?)'; $params[] = "%{$q}%"; $params[] = "%{$q}%"; }
        $where = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM borrow_requests {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT * FROM borrow_requests {$where} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $query = http_build_query(array_filter(['status' => $status, 'q' => $q], static fn ($v) => $v !== ''));
        $base  = admin_url('/peminjaman-barang') . ($query !== '' ? '?' . $query : '');

        $this->adminView('admin/pages/borrows/index', [
            'title'      => 'Peminjaman Barang',
            'rows'       => $rows,
            'status'     => $status,
            'q'          => $q,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function show(string $id): void
    {
        $request = $this->db()->selectOne('SELECT * FROM borrow_requests WHERE id = ?', [$id]);
        if ($request === null) {
            Session::flash('error', 'Pengajuan peminjaman tidak ditemukan.');
            $this->redirect(admin_url('/peminjaman-barang'));
        }

        $this->adminView('admin/pages/borrows/show', [
            'title'   => 'Detail Peminjaman',
            'request' => $request,
            'items'   => $this->db()->select(
                "SELECT bi.*, a.code AS asset_code, a.condition AS asset_condition
                 FROM borrow_items bi LEFT JOIN assets a ON a.id = bi.asset_id
                 WHERE bi.borrow_request_id = ? ORDER BY bi.id",
                [$id]
            ),
        ]);
    }

    public function verify(string $id): void
    {
        $request = $this->find($id);
        $this->db()->update('borrow_requests', ['status' => 'Diverifikasi'], ['id' => $id]);
        AuditLogger::log('borrow.verify', 'borrow', $id);
        Session::flash('success', 'Peminjaman telah diverifikasi.');
        $this->redirect(admin_url('/peminjaman-barang/' . $id));
    }

    public function approve(string $id): void
    {
        $request  = $this->find($id);
        $decision = Request::input('decision', '');

        if (!in_array($decision, ['Disetujui', 'Ditolak'], true)) {
            Session::flash('error', 'Keputusan tidak valid.');
            $this->redirect(admin_url('/peminjaman-barang/' . $id));
        }

        $update = [
            'approval_status' => $decision,
            'approved_by'     => auth_id(),
            'approved_at'     => date('Y-m-d H:i:s'),
        ];
        if ($decision === 'Ditolak') {
            $update['status'] = 'Ditolak';
        }

        $this->db()->update('borrow_requests', $update, ['id' => $id]);
        AuditLogger::log('borrow.' . strtolower($decision), 'borrow', $id);
        Session::flash('success', 'Keputusan persetujuan telah disimpan.');
        $this->redirect(admin_url('/peminjaman-barang/' . $id));
    }

    public function handover(string $id): void
    {
        $request = $this->find($id);

        if ($request['approval_status'] !== 'Disetujui') {
            Session::flash('error', 'Peminjaman belum disetujui.');
            $this->redirect(admin_url('/peminjaman-barang/' . $id));
        }

        $items = $this->db()->select('SELECT * FROM borrow_items WHERE borrow_request_id = ?', [$id]);

        $this->db()->beginTransaction();
        try {
            foreach ($items as $item) {
                if ($item['asset_id'] === null) {
                    continue;
                }
                $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$item['asset_id']]);
                if ($asset === null) {
                    continue;
                }
                $this->db()->update('borrow_items', ['condition_before' => $asset['condition']], ['id' => $item['id']]);
                $this->db()->update('assets', ['status' => 'Dipinjamkan'], ['id' => $asset['id']]);
            }

            $this->db()->update('borrow_requests', [
                'status'      => 'Dipinjam',
                'borrow_date' => date('Y-m-d H:i:s'),
            ], ['id' => $id]);
            $this->db()->commit();
        } catch (\Throwable $e) {
            $this->db()->rollBack();
            Session::flash('error', 'Gagal memproses serah terima.');
            $this->redirect(admin_url('/peminjaman-barang/' . $id));
        }

        AuditLogger::log('borrow.handover', 'borrow', $id);
        Session::flash('success', 'Barang telah diserahkan kepada peminjam.');
        $this->redirect(admin_url('/peminjaman-barang/' . $id));
    }

    public function returnBack(string $id): void
    {
        $request = $this->find($id);
        $conditionAfter = Request::input('condition_after', 'Baik');
        $notes = Request::input('notes', '');

        $items = $this->db()->select('SELECT * FROM borrow_items WHERE borrow_request_id = ?', [$id]);

        $this->db()->beginTransaction();
        try {
            foreach ($items as $item) {
                $this->db()->update('borrow_items', [
                    'returned_quantity' => $item['quantity'],
                    'condition_after'   => $conditionAfter,
                    'notes'             => $notes !== '' ? $notes : $item['notes'],
                ], ['id' => $item['id']]);

                if ($item['asset_id'] !== null) {
                    $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$item['asset_id']]);
                    if ($asset === null) {
                        continue;
                    }

                    $this->db()->update('assets', ['status' => 'Aktif'], ['id' => $asset['id']]);

                    if ($asset['condition'] !== $conditionAfter) {
                        $this->db()->update('assets', ['condition' => $conditionAfter], ['id' => $asset['id']]);
                        $this->db()->insert('asset_condition_histories', [
                            'asset_id'      => (int) $asset['id'],
                            'old_condition' => $asset['condition'],
                            'new_condition' => $conditionAfter,
                            'note'          => 'Perubahan kondisi setelah pengembalian peminjaman ' . $request['borrow_code'] . '.',
                            'changed_by'    => auth_id(),
                        ]);
                    }
                }
            }

            $this->db()->update('borrow_requests', [
                'status'             => 'Selesai',
                'actual_return_date' => date('Y-m-d H:i:s'),
            ], ['id' => $id]);
            $this->db()->commit();
        } catch (\Throwable $e) {
            $this->db()->rollBack();
            Session::flash('error', 'Gagal memproses pengembalian.');
            $this->redirect(admin_url('/peminjaman-barang/' . $id));
        }

        AuditLogger::log('borrow.return', 'borrow', $id);
        Session::flash('success', 'Pengembalian barang telah dicatat.');
        $this->redirect(admin_url('/peminjaman-barang/' . $id));
    }

    private function find(string $id): array
    {
        $request = $this->db()->selectOne('SELECT * FROM borrow_requests WHERE id = ?', [$id]);
        if ($request === null) {
            Session::flash('error', 'Pengajuan peminjaman tidak ditemukan.');
            $this->redirect(admin_url('/peminjaman-barang'));
        }
        return $request;
    }
}