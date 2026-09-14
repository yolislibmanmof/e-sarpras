<div class="page-head">
    <h1>Vendor Eksternal</h1>
    <p>Daftar vendor yang dapat ditugaskan untuk perbaikan atau pengadaan jasa.</p>
</div>

<div class="grid-2">
    <div class="table-card">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Nama</th><th>Layanan</th><th>Kontak</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($rows === []): ?>
                        <tr><td colspan="4" class="empty-note">Belum ada vendor.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= e($row['name']); ?></td>
                                <td><?= e($row['services'] ?? '-'); ?></td>
                                <td><?= e($row['phone'] ?? '-'); ?></td>
                                <td>
                                    <form method="POST" action="<?= admin_url('/vendor/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus vendor ini?">
                                        <?= csrf_field(); ?>
                                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Tambah Vendor</h3>
        <form method="POST" action="<?= admin_url('/vendor'); ?>">
            <?= csrf_field(); ?>
            <label for="name">Nama *</label>
            <input id="name" name="name" required>
            <label for="contact_person">Narahubung</label>
            <input id="contact_person" name="contact_person">
            <label for="services">Layanan</label>
            <input id="services" name="services" placeholder="Contoh: Servis AC, Lift, Kelistrikan">
            <label for="phone">Kontak</label>
            <input id="phone" name="phone">
            <div class="form-actions">
                <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>