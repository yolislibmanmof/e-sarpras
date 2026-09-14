<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class TechnicianController extends Controller
{
    public function index(): void
    {
        $this->adminView('admin/pages/technicians/index', [
            'title' => 'Teknisi',
            'rows'  => $this->db()->select('SELECT * FROM technicians ORDER BY name'),
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['name' => 'required|max:191']);

        if ($errors !== []) {
            Session::flash('error', 'Nama teknisi wajib diisi.');
            $this->redirect(admin_url('/teknisi'));
        }

        $id = $this->db()->insert('technicians', [
            'name'           => $data['name'],
            'employee_code'  => $data['employee_code'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'phone'          => $data['phone'] ?? null,
            'is_active'      => 1,
        ]);

        AuditLogger::log('technician.create', 'ticket', $id, null, ['name' => $data['name']]);
        Session::flash('success', 'Teknisi berhasil ditambahkan.');
        $this->redirect(admin_url('/teknisi'));
    }

    public function destroy(string $id): void
    {
        $assigned = $this->db()->count('tickets', ['assigned_technician_id' => $id]);
        if ($assigned > 0) {
            Session::flash('error', 'Teknisi tidak dapat dihapus karena masih memiliki tugas.');
            $this->redirect(admin_url('/teknisi'));
        }

        $this->db()->delete('technicians', ['id' => $id]);
        AuditLogger::log('technician.delete', 'ticket', $id);
        Session::flash('success', 'Teknisi berhasil dihapus.');
        $this->redirect(admin_url('/teknisi'));
    }
}