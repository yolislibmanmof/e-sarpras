<div class="page-head">
    <h1><?= e($title); ?></h1>
    <p>Centang izin yang ingin diberikan kepada role ini.</p>
</div>

<form method="POST" action="<?= admin_url('/role/' . $role['id']); ?>">
    <?= csrf_field(); ?>

    <?php foreach ($groups as $group => $permissions): ?>
        <div class="card" style="margin-bottom:1.2rem;">
            <h3 class="card-title" style="text-transform:capitalize;"><?= e(str_replace('_', ' ', $group)); ?></h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.4rem 1.2rem;">
                <?php foreach ($permissions as $p): ?>
                    <div class="checkbox-row">
                        <input type="checkbox" id="perm<?= (int) $p['id']; ?>" name="permissions[]" value="<?= (int) $p['id']; ?>" <?= (int) $p['has'] > 0 ? 'checked' : ''; ?>>
                        <label for="perm<?= (int) $p['id']; ?>"><code><?= e($p['code']); ?></code></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan Izin</button>
        <a class="btn btn-secondary" href="<?= admin_url('/role'); ?>">Kembali</a>
    </div>
</form>