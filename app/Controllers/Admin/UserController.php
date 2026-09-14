<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class UserController extends Controller
{
    public function index(): void
    {
        $q = Request::input('q', '');
        $page = max(1, (int) Request::input('page', 1));
        $perPage = 10;

        $where = ''; $params = [];
        if ($q !== '') { $where = 'WHERE u.full_name LIKE ? OR u.username LIKE ? OR u.email LIKE ?'; $params = ["%{$q}%", "%{$q}%", "%{$q}%"]; }

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM users u {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT u.*, (SELECT GROUP_CONCAT(r.name SEPARATOR ', ') FROM user_roles ur JOIN roles r ON r.id = ur.role_id WHERE ur.user_id = u.id) AS role_names
             FROM users u {$where} ORDER BY u.full_name ASC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $base = admin_url('/pengguna') . ($q !== '' ? '?q=' . urlencode($q) : '');

        $this->adminView('admin/pages/users/index', [
            'title' => 'Pengguna', 'rows' => $rows, 'q' => $q,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/pages/users/form', [
            'title' => 'Tambah Pengguna', 'user' => null, 'userRoles' => [],
            'roles' => $this->db()->select('SELECT * FROM roles ORDER BY name'),
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'username' => 'required|max:100', 'full_name' => 'required|max:191', 'password' => 'required|min:6',
        ]);
        if ($errors === [] && $this->db()->selectOne('SELECT id FROM users WHERE username = ?', [$data['username']]) !== null) {
            $errors = ['username' => ['Username sudah digunakan.']];
        }

        if ($errors !== []) { Session::flash('errors', $errors); Session::flash('old', $data); $this->redirect(admin_url('/pengguna/tambah')); }

        $id = $this->db()->insert('users', [
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'full_name' => $data['full_name'],
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ]);

        $this->syncRoles($id, $data['roles'] ?? []);

        AuditLogger::log('user.create', 'user', $id, null, ['username' => $data['username']]);
        Session::flash('success', 'Pengguna berhasil dibuat.');
        $this->redirect(admin_url('/pengguna'));
    }

    public function edit(string $id): void
    {
        $user = $this->db()->selectOne('SELECT * FROM users WHERE id = ?', [$id]);
        if ($user === null) { Session::flash('error', 'Pengguna tidak ditemukan.'); $this->redirect(admin_url('/pengguna')); }

        $userRoles = array_column($this->db()->select('SELECT role_id FROM user_roles WHERE user_id = ?', [$id]), 'role_id');

        $this->adminView('admin/pages/users/form', [
            'title' => 'Ubah Pengguna', 'user' => $user, 'userRoles' => $userRoles,
            'roles' => $this->db()->select('SELECT * FROM roles ORDER BY name'),
        ]);
    }

    public function update(string $id): void
    {
        $user = $this->db()->selectOne('SELECT * FROM users WHERE id = ?', [$id]);
        if ($user === null) { Session::flash('error', 'Pengguna tidak ditemukan.'); $this->redirect(admin_url('/pengguna')); }

        $data = Request::all();
        $errors = $this->validate($data, ['username' => 'required|max:100', 'full_name' => 'required|max:191']);
        if ($errors === [] && $this->db()->selectOne('SELECT id FROM users WHERE username = ? AND id <> ?', [$data['username'], $id]) !== null) {
            $errors = ['username' => ['Username sudah digunakan.']];
        }
        if ($errors !== []) { Session::flash('errors', $errors); Session::flash('old', $data); $this->redirect(admin_url('/pengguna/' . $id . '/ubah')); }

        $update = [
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'full_name' => $data['full_name'],
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ];
        if (($data['password'] ?? '') !== '') {
            $update['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->db()->update('users', $update, ['id' => $id]);
        $this->syncRoles($id, $data['roles'] ?? []);

        AuditLogger::log('user.update', 'user', $id, $user, $data);
        Session::flash('success', 'Pengguna berhasil diperbarui.');
        $this->redirect(admin_url('/pengguna'));
    }

    public function toggle(string $id): void
    {
        $user = $this->db()->selectOne('SELECT * FROM users WHERE id = ?', [$id]);
        if ($user === null) { Session::flash('error', 'Pengguna tidak ditemukan.'); $this->redirect(admin_url('/pengguna')); }
        if ((int) $id === auth_id()) { Session::flash('error', 'Anda tidak dapat menonaktifkan akun sendiri.'); $this->redirect(admin_url('/pengguna')); }

        $this->db()->update('users', ['is_active' => (int) $user['is_active'] === 1 ? 0 : 1], ['id' => $id]);
        AuditLogger::log('user.toggle', 'user', $id);
        Session::flash('success', 'Status pengguna diperbarui.');
        $this->redirect(admin_url('/pengguna'));
    }

    public function destroy(string $id): void
    {
        if ((int) $id === auth_id()) { Session::flash('error', 'Anda tidak dapat menghapus akun sendiri.'); $this->redirect(admin_url('/pengguna')); }

        $user = $this->db()->selectOne('SELECT * FROM users WHERE id = ?', [$id]);
        if ($user === null) { Session::flash('error', 'Pengguna tidak ditemukan.'); $this->redirect(admin_url('/pengguna')); }

        $this->db()->delete('user_roles', ['user_id' => $id]);
        $this->db()->delete('users', ['id' => $id]);
        AuditLogger::log('user.delete', 'user', $id, $user, null);
        Session::flash('success', 'Pengguna berhasil dihapus.');
        $this->redirect(admin_url('/pengguna'));
    }

    private function syncRoles(int $userId, array $roleIds): void
    {
        $this->db()->delete('user_roles', ['user_id' => $userId]);
        foreach ($roleIds as $roleId) {
            $this->db()->insert('user_roles', ['user_id' => $userId, 'role_id' => (int) $roleId]);
        }
    }
}