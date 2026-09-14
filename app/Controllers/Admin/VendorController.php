<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class VendorController extends Controller
{
    public function index(): void
    {
        $this->adminView('admin/pages/vendors/index', [
            'title' => 'Vendor',
            'rows'  => $this->db()->select('SELECT * FROM vendors ORDER BY name'),
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['name' => 'required|max:191']);

        if ($errors !== []) {
            Session::flash('error', 'Nama vendor wajib diisi.');
            $this->redirect(admin_url('/vendor'));
        }

        $id = $this->db()->insert('vendors', [
            'name'           => $data['name'],
            'contact_person' => $data['contact_person'] ?? null,
            'phone'          => $data['phone'] ?? null,
            'email'          => $data['email'] ?? null,
            'services'       => $data['services'] ?? null,
        ]);

        AuditLogger::log('vendor.create', 'ticket', $id, null, ['name' => $data['name']]);
        Session::flash('success', 'Vendor berhasil ditambahkan.');
        $this->redirect(admin_url('/vendor'));
    }

    public function destroy(string $id): void
    {
        $assigned = $this->db()->count('tickets', ['assigned_vendor_id' => $id]);
        if ($assigned > 0) {
            Session::flash('error', 'Vendor tidak dapat dihapus karena masih memiliki tugas.');
            $this->redirect(admin_url('/vendor'));
        }

        $this->db()->delete('vendors', ['id' => $id]);
        AuditLogger::log('vendor.delete', 'ticket', $id);
        Session::flash('success', 'Vendor berhasil dihapus.');
        $this->redirect(admin_url('/vendor'));
    }
}