<div class="page-head">
    <h1>Manajemen Pengguna</h1>
    <p>Kelola akun petugas, pimpinan, dan auditor sistem.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/pengguna'); ?>" class="toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari nama/username/email...">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
        </form>
        <div class="row-actions">
            <a class="btn btn-primary btn-sm" href="<?= admin_url('/pengguna/tambah'); ?>">+ Tambah Pengguna</a>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Pengguna</th><th>Username</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="5" class="empty-note">Belum ada pengguna.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><strong><?= e($row['full_name']); ?></strong><br><small style="color:var(--muted);"><?= e($row['email'] ?? '-'); ?></small></td>
                            <td><?= e($row['username']); ?></td>
                            <td><?= e($row['role_names'] ?? 'Tanpa role'); ?></td>
                            <td>
                                <span class="badge <?= (int) $row['is_active'] === 1 ? 'badge-success' : 'badge-danger'; ?>">
                                    <?= (int) $row['is_active'] === 1 ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn btn-secondary btn-sm" href="<?= admin_url('/pengguna/' . $row['id'] . '/ubah'); ?>">Ubah</a>
                                    <form method="POST" action="<?= admin_url('/pengguna/' . $row['id'] . '/status'); ?>">
                                        <?= csrf_field(); ?>
                                        <button class="btn btn-secondary btn-sm" type="submit"><?= (int) $row['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan'; ?></button>
                                    </form>
                                    <form method="POST" action="<?= admin_url('/pengguna/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus pengguna ini?">
                                        <?= csrf_field(); ?>
                                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>