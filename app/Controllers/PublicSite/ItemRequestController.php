<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class ItemRequestController extends Controller
{
    private const REQUESTER_TYPES = ['Dosen', 'Tendik', 'Prodi', 'Fakultas', 'Unit'];

    public function form(): void
    {
        $db = $this->db();
        $code   = Request::input('code', '');
        $result = null;

        if ($code !== '') {
            $result = $db->selectOne('SELECT * FROM item_requests WHERE request_code = ?', [$code]);
        }

        $this->view('public/pages/item_request', [
            'title'         => 'Permintaan Barang',
            'requesterTypes'=> self::REQUESTER_TYPES,
            'stocks'        => $db->select("SELECT id, item_code, item_name, unit FROM stocks WHERE quantity > 0 ORDER BY item_name"),
            'lowStock'      => $db->select("SELECT item_code, item_name, quantity, minimum_quantity FROM stocks WHERE quantity <= minimum_quantity ORDER BY item_name LIMIT 6"),
            'code'          => $code,
            'result'        => $result,
        ], 'public/layouts/main');
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'requester_name' => 'required|max:191',
            'unit_name'      => 'required|max:191',
            'purpose'        => 'required|min:10',
        ]);

        $items = [];
        foreach ($_POST['items'] ?? [] as $row) {
            $stockId = (int) ($row['stock_id'] ?? 0);
            $name    = trim((string) ($row['item_name'] ?? ''));
            $qty     = (int) ($row['quantity'] ?? 0);
            if ($qty <= 0) { continue; }
            if ($name === '' && $stockId > 0) {
                $stock = $this->db()->selectOne('SELECT * FROM stocks WHERE id = ?', [$stockId]);
                $name  = $stock['item_name'] ?? '';
            }
            if ($name !== '') { $items[] = ['stock_id' => $stockId > 0 ? $stockId : null, 'item_name' => $name, 'quantity' => $qty]; }
        }
        if ($items === []) { $errors['items'] = ['Isi minimal satu barang yang diminta.']; }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(base_url('/permintaan-barang'));
        }

        $count = $this->db()->count('item_requests');
        do {
            $count++;
            $code = 'REQ-' . date('Ymd') . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        } while ($this->db()->selectOne('SELECT id FROM item_requests WHERE request_code = ?', [$code]) !== null);

        $requestId = $this->db()->insert('item_requests', [
            'request_code'    => $code,
            'request_type'    => ($data['request_type'] ?? 'ATK') === 'Elektronik' ? 'Elektronik' : 'ATK',
            'requester_type'  => in_array($data['requester_type'] ?? '', self::REQUESTER_TYPES, true) ? $data['requester_type'] : 'Dosen',
            'requester_name'  => $data['requester_name'],
            'unit_name'       => $data['unit_name'],
            'purpose'         => $data['purpose'],
            'needed_date'     => ($data['needed_date'] ?? '') === '' ? null : $data['needed_date'],
            'status'          => 'Menunggu Verifikasi',
            'approval_status' => 'Menunggu',
        ]);

        foreach ($items as $item) {
            $this->db()->insert('item_request_items', [
                'item_request_id' => $requestId,
                'stock_id'        => $item['stock_id'],
                'item_name'       => $item['item_name'],
                'quantity'        => $item['quantity'],
            ]);
        }

        AuditLogger::log('item_request.create', 'item_request', $requestId, null, ['request_code' => $code]);
        Session::flash('success', 'Permintaan barang diterima. Kode: ' . $code);
        $this->redirect(base_url('/permintaan-barang?code=' . urlencode($code)));
    }
}