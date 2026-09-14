<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class FloorController extends Controller
{
    public function store(string $id): void
    {
        $building = $this->db()->selectOne('SELECT * FROM buildings WHERE id = ?', [$id]);
        if ($building === null) {
            Session::flash('error', 'Gedung tidak ditemukan.');
            $this->redirect(admin_url('/gedung'));
        }

        $data = Request::all();
        $errors = $this->validate($data, [
            'level' => 'required|numeric',
            'name'  => 'required|max:191',
        ]);

        if ($errors !== []) {
            Session::flash('error', 'Data lantai tidak valid. Periksa kembali nomor dan nama lantai.');
            $this->redirect(admin_url('/gedung/' . $id . '/ubah'));
        }

        $exists = $this->db()->selectOne('SELECT id FROM floors WHERE building_id = ? AND level = ?', [$id, (int) $data['level']]);
        if ($exists !== null) {
            Session::flash('error', 'Nomor lantai tersebut sudah ada pada gedung ini.');
            $this->redirect(admin_url('/gedung/' . $id . '/ubah'));
        }

        $floorId = $this->db()->insert('floors', [
            'building_id' => (int) $id,
            'level'       => (int) $data['level'],
            'name'        => $data['name'],
            'notes'       => $data['notes'] ?? null,
        ]);

        AuditLogger::log('floor.create', 'floor', $floorId, null, ['building_id' => $id, 'level' => $data['level']]);
        Session::flash('success', 'Lantai berhasil ditambahkan.');
        $this->redirect(admin_url('/gedung/' . $id . '/ubah'));
    }

    public function destroy(string $id): void
    {
        $floor = $this->db()->selectOne('SELECT * FROM floors WHERE id = ?', [$id]);
        if ($floor === null) {
            Session::flash('error', 'Lantai tidak ditemukan.');
            $this->redirect(admin_url('/gedung'));
        }

        $rooms = $this->db()->count('rooms', ['floor_id' => $id]);
        if ($rooms > 0) {
            Session::flash('error', 'Lantai tidak dapat dihapus karena masih memiliki ruangan.');
            $this->redirect(admin_url('/gedung/' . $floor['building_id'] . '/ubah'));
        }

        $this->db()->delete('floors', ['id' => $id]);
        AuditLogger::log('floor.delete', 'floor', $id, $floor, null);
        Session::flash('success', 'Lantai berhasil dihapus.');
        $this->redirect(admin_url('/gedung/' . $floor['building_id'] . '/ubah'));
    }
}