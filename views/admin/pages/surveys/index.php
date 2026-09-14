<div class="page-head">
    <h1>Survei Kepuasan</h1>
    <p>Pantau tren kualitas pelayanan sarpras melalui survei pengguna.</p>
</div>

<div class="table-card" style="margin-bottom:1.4rem;">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Judul</th><th>Target</th><th>Respons</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="5" class="empty-note">Belum ada survei.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['title']); ?></td>
                            <td><?= e($row['target_audience'] ?? 'Umum'); ?></td>
                            <td><?= (int) $row['response_count']; ?></td>
                            <td>
                                <span class="badge <?= (int) $row['is_active'] === 1 ? 'badge-success' : 'badge-info'; ?>">
                                    <?= (int) $row['is_active'] === 1 ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn btn-secondary btn-sm" href="<?= admin_url('/survei/' . $row['id']); ?>">Kelola</a>
                                    <?php if (can('survey.update')): ?>
                                        <form method="POST" action="<?= admin_url('/survei/' . $row['id'] . '/status'); ?>">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-secondary btn-sm" type="submit"><?= (int) $row['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan'; ?></button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (can('survey.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/survei/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus survei ini?">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="form-card">
    <h3 class="card-title">Buat Survei Baru</h3>
    <form method="POST" action="<?= admin_url('/survei'); ?>">
        <?= csrf_field(); ?>
        <div class="form-grid">
            <div class="form-full"><label for="title">Judul *</label><input id="title" name="title" required></div>
            <div><label for="target_audience">Target Responden</label><input id="target_audience" name="target_audience" placeholder="Mahasiswa / Dosen / Tendik"></div>
            <div class="checkbox-row" style="align-self:end;">
                <input type="checkbox" id="is_active" name="is_active" checked>
                <label for="is_active">Langsung aktif</label>
            </div>
            <div class="form-full"><label for="description">Deskripsi</label><textarea id="description" name="description" rows="2"></textarea></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan Survei</button></div>
    </form>
</div>