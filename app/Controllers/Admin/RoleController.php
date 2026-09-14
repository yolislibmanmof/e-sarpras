<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class RoleController extends Controller
{
    private const PROTECTED = ['super-admin-sarpras'];

    public function index(): void
    {
        $rows = $this->db()->select(
            "SELECT r.*,
                (SELECT COUNT(*) FROM user_roles ur WHERE ur.role_id = r.id) AS user_count,
                (SELECT COUNT(*) FROM role_permissions rp WHERE rp.role_id = r.id) AS perm_count
             FROM roles r ORDER BY r.name"
        );

        $this->adminView('admin/pages/roles/index', ['title' => 'Role & Izin', 'rows' => $rows]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['code' => 'required|max:100', 'name' => 'required|max:191']);
        if ($errors === [] && $this->db()->selectOne('SELECT id FROM roles WHERE code = ?', [$data['code']]) !== null) {
            $errors = ['code' => ['Kode role sudah digunakan.']];
        }
        if ($errors !== []) { Session::flash('error', reset($errors)[0]); $this->redirect(admin_url('/role')); }

        $id = $this->db()->insert('roles', [
            'code' => strtolower(preg_replace('/[^a-z0-9-]+/i', '-', $data['code'])),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        AuditLogger::log('role.create', 'role', $id);
        Session::flash('success', 'Role berhasil dibuat. Atur izinnya melalui tombol Kelola.');
        $this->redirect(admin_url('/role'));
    }

    public function show(string $id): void
    {
        $role = $this->db()->selectOne('SELECT * FROM roles WHERE id = ?', [$id]);
        if ($role === null) { Session::flash('error', 'Role tidak ditemukan.'); $this->redirect(admin_url('/role')); }

        $permissions = $this->db()->select(
            "SELECT p.*, (SELECT COUNT(*) FROM role_permissions rp WHERE rp.permission_id = p.id AND rp.role_id = ?) AS has FROM permissions p ORDER BY p.code",
            [$id]
        );

        $groups = [];
        foreach ($permissions as $p) {
            $prefix = explode('.', $p['code'])[0];
            $groups[$prefix][] = $p;
        }

        $this->adminView('admin/pages/roles/show', [
            'title' => 'Izin Role: ' . $role['name'],
            'role' => $role,
            'groups' => $groups,
        ]);
    }

    public function updatePermissions(string $id): void
    {
        $role = $this->db()->selectOne('SELECT * FROM roles WHERE id = ?', [$id]);
        if ($role === null) { Session::flash('error', 'Role tidak ditemukan.'); $this->redirect(admin_url('/role')); }

        $checked = Request::input('permissions', []);
        if (!is_array($checked)) { $checked = []; }

        $this->db()->delete('role_permissions', ['role_id' => $id]);
        foreach ($checked as $permId) {
            $this->db()->insert('role_permissions', ['role_id' => (int) $id, 'permission_id' => (int) $permId]);
        }

        AuditLogger::log('role.permissions.update', 'role', $id, null, ['count' => count($checked)]);
        Session::flash('success', 'Izin role berhasil disimpan.');
        $this->redirect(admin_url('/role/' . $id));
    }

    public function destroy(string $id): void
    {
        $role = $this->db()->selectOne('SELECT * FROM roles WHERE id = ?', [$id]);
        if ($role === null) { Session::flash('error', 'Role tidak ditemukan.'); $this->redirect(admin_url('/role')); }
        if (in_array($role['code'], self::PROTECTED, true)) { Session::flash('error', 'Role ini terlindungi dan tidak dapat dihapus.'); $this->redirect(admin_url('/role')); }
        if ($this->db()->count('user_roles', ['role_id' => $id]) > 0) { Session::flash('error', 'Role masih dipakai pengguna dan tidak dapat dihapus.'); $this->redirect(admin_url('/role')); }

        $this->db()->delete('role_permissions', ['role_id' => $id]);
        $this->db()->delete('roles', ['id' => $id]);
        AuditLogger::log('role.delete', 'role', $id, $role, null);
        Session::flash('success', 'Role berhasil dihapus.');
        $this->redirect(admin_url('/role'));
    }
}