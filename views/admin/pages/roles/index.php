<div class="page-head">
    <h1>Role &amp; Izin</h1>
    <p>Kelola role dan hak akses (RBAC) seluruh pengguna.</p>
</div>

<div class="table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Role</th><th>Kode</th><th>Pengguna</th><th>Izin</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><strong><?= e($row['name']); ?></strong><br><small style="color:var(--muted);"><?= e($row['description'] ?? ''); ?></small></td>
                        <td><code><?= e($row['code']); ?></code></td>
                        <td><?= (int) $row['user_count']; ?></td>
                        <td><?= (int) $row['perm_count']; ?></td>
                        <td>
                            <div class="row-actions">
                                <a class="btn btn-secondary btn-sm" href="<?= admin_url('/role/' . $row['id']); ?>">Kelola Izin</a>
                                <?php if ($row['code'] !== 'super-admin-sarpras'): ?>
                                    <form method="POST" action="<?= admin_url('/role/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus role ini?">
                                        <?= csrf_field(); ?>
                                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="form-card">
    <h3 class="card-title">Tambah Role</h3>
    <form method="POST" action="<?= admin_url('/role'); ?>">
        <?= csrf_field(); ?>
        <div class="form-grid">
            <div><label for="code">Kode *</label><input id="code" name="code" placeholder="contoh: staf-keuangan" required></div>
            <div><label for="name">Nama *</label><input id="name" name="name" required></div>
            <div class="form-full"><label for="description">Deskripsi</label><input id="description" name="description"></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan Role</button></div>
    </form>
</div>