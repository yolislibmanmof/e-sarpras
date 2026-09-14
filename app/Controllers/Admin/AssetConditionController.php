<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class AssetConditionController extends Controller
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
            'new_condition' => 'required',
            'note'          => 'required|min:5',
        ]);

        if ($errors !== []) {
            Session::flash('error', 'Data perubahan kondisi tidak lengkap.');
            $this->redirect(admin_url('/aset/' . $id));
        }

        $this->db()->beginTransaction();
        try {
            $this->db()->insert('asset_condition_histories', [
                'asset_id'      => (int) $id,
                'old_condition' => $asset['condition'],
                'new_condition' => $data['new_condition'],
                'note'          => $data['note'],
                'changed_by'    => auth_id(),
            ]);
            $this->db()->update('assets', ['condition' => $data['new_condition']], ['id' => $id]);
            $this->db()->commit();
        } catch (\Throwable $e) {
            $this->db()->rollBack();
            Session::flash('error', 'Gagal memperbarui kondisi aset.');
            $this->redirect(admin_url('/aset/' . $id));
        }

        AuditLogger::log('asset_condition.update', 'asset', $id, ['condition' => $asset['condition']], ['condition' => $data['new_condition']]);
        Session::flash('success', 'Kondisi aset berhasil diperbarui.');
        $this->redirect(admin_url('/aset/' . $id));
    }
}