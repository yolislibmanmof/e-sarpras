<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Security\AuditLogger;

class AssetLabelController extends Controller
{
    public function store(string $id): void
    {
        $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$id]);
        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $count = $this->db()->count('asset_labels', ['asset_id' => $id]);
        $code  = 'LBL-' . $asset['code'] . '-' . str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);

        $labelId = $this->db()->insert('asset_labels', [
            'asset_id'   => (int) $id,
            'label_code' => $code,
            'qr_code'    => base_url('/aset/' . $id),
            'barcode'    => $code,
            'status'     => 'Aktif',
            'created_by' => auth_id(),
        ]);

        AuditLogger::log('asset_label.create', 'asset', $labelId, null, ['label_code' => $code]);
        Session::flash('success', 'Label inventaris berhasil dibuat.');
        $this->redirect(admin_url('/aset/' . $id));
    }

    public function print(string $id): void
    {
        $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$id]);
        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $labels = $this->db()->select('SELECT * FROM asset_labels WHERE asset_id = ? ORDER BY id ASC', [$id]);

        $this->db()->execute('UPDATE asset_labels SET printed_at = ? WHERE asset_id = ?', [date('Y-m-d H:i:s'), $id]);

        $this->view('admin/pages/assets/labels_print', [
            'asset'       => $asset,
            'labels'      => $labels,
            'campus_name' => setting_value('campus_name', 'Kampus'),
        ]);
    }

    public function destroy(string $id): void
    {
        $label = $this->db()->selectOne('SELECT * FROM asset_labels WHERE id = ?', [$id]);
        if ($label === null) {
            Session::flash('error', 'Label tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $this->db()->delete('asset_labels', ['id' => $id]);
        AuditLogger::log('asset_label.delete', 'asset', $id, $label, null);
        Session::flash('success', 'Label inventaris berhasil dihapus.');
        $this->redirect(admin_url('/aset/' . $label['asset_id']));
    }
}