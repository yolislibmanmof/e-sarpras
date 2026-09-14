<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $isEdit = $user !== null; ?>
<?php $current = $isEdit ? $user + $old : $old; ?>

<div class="page-head">
    <h1><?= $isEdit ? 'Ubah Pengguna' : 'Tambah Pengguna'; ?></h1>
    <p>Tentukan identitas akun dan role akses.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= $isEdit ? admin_url('/pengguna/' . $user['id'] . '/ubah') : admin_url('/pengguna'); ?>">
        <?= csrf_field(); ?>
        <div class="form-grid">
            <div><label for="full_name">Nama Lengkap *</label><input id="full_name" name="full_name" value="<?= e($current['full_name'] ?? ''); ?>" required></div>
            <div><label for="username">Username *</label><input id="username" name="username" value="<?= e($current['username'] ?? ''); ?>" required></div>
            <div><label for="email">Email</label><input id="email" name="email" type="email" value="<?= e($current['email'] ?? ''); ?>"></div>
            <div>
                <label for="password"><?= $isEdit ? 'Password Baru (kosongkan bila tetap)' : 'Password *'; ?></label>
                <input id="password" name="password" type="password" <?= $isEdit ? '' : 'required minlength="6"'; ?>>
            </div>
            <div class="form-full">
                <label>Role Akses</label>
                <?php foreach ($roles as $role): ?>
                    <div class="checkbox-row">
                        <input type="checkbox" id="role<?= (int) $role['id']; ?>" name="roles[]" value="<?= (int) $role['id']; ?>" <?= in_array((string) $role['id'], array_map('strval', $userRoles), true) ? 'checked' : ''; ?>>
                        <label for="role<?= (int) $role['id']; ?>"><?= e($role['name']); ?> <small style="color:var(--muted);">(<?= e($role['code']); ?>)</small></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="checkbox-row">
                <input type="checkbox" id="is_active" name="is_active" <?= (int) ($current['is_active'] ?? 1) === 1 ? 'checked' : ''; ?>>
                <label for="is_active">Akun aktif</label>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="<?= admin_url('/pengguna'); ?>">Kembali</a>
        </div>
    </form>
</div>