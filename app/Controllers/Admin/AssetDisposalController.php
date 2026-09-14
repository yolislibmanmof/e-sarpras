<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class AssetDisposalController extends Controller
{
    public function store(string $id): void
    {
        $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$id]);
        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $data = Request::all();
        $errors = $this->validate($data, [
            'reason' => 'required|min:10',
        ]);

        if ($errors !== []) {
            Session::flash('error', 'Alasan penghapusan aset wajib diisi minimal 10 karakter.');
            $this->redirect(admin_url('/aset/' . $id));
        }

        $disposalId = $this->db()->insert('asset_disposals', [
            'asset_id'        => (int) $id,
            'reason'          => $data['reason'],
            'condition'       => $data['condition'] ?? $asset['condition'],
            'approval_status' => 'Menunggu',
            'created_by'      => auth_id(),
        ]);

        AuditLogger::log('asset_disposal.request', 'asset', $disposalId, null, ['asset_id' => $id]);
        Session::flash('success', 'Usulan penghapusan aset telah diajukan.');
        $this->redirect(admin_url('/aset/' . $id));
    }

    public function approve(string $id): void
    {
        $disposal = $this->db()->selectOne('SELECT * FROM asset_disposals WHERE id = ?', [$id]);
        if ($disposal === null) {
            Session::flash('error', 'Usulan penghapusan tidak ditemukan.');
            $this->redirect(admin_url('/aset'));
        }

        $decision = Request::input('decision', '');
        if (!in_array($decision, ['Disetujui', 'Ditolak'], true)) {
            Session::flash('error', 'Keputusan tidak valid.');
            $this->redirect(admin_url('/aset/' . $disposal['asset_id']));
        }

        $this->db()->update('asset_disposals', [
            'approval_status' => $decision,
            'approved_by'     => auth_id(),
            'approved_at'     => date('Y-m-d H:i:s'),
        ], ['id' => $id]);

        if ($decision === 'Disetujui') {
            $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$disposal['asset_id']]);
            $this->db()->update('assets', [
                'status'    => 'Dihapuskan',
                'condition' => 'Tidak Layak Pakai',
            ], ['id' => $disposal['asset_id']]);

            $this->db()->insert('asset_condition_histories', [
                'asset_id'      => (int) $disposal['asset_id'],
                'old_condition' => $asset['condition'] ?? null,
                'new_condition' => 'Tidak Layak Pakai',
                'note'          => 'Penghapusan aset disetujui: ' . $disposal['reason'],
                'changed_by'    => auth_id(),
            ]);
        }

        AuditLogger::log('asset_disposal.' . ($decision === 'Disetujui' ? 'approved' : 'rejected'), 'asset', $id, null, ['asset_id' => $disposal['asset_id']]);
        Session::flash('success', 'Keputusan penghapusan aset telah disimpan.');
        $this->redirect(admin_url('/aset/' . $disposal['asset_id']));
    }
}