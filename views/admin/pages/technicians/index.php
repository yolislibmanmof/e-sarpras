<div class="page-head">
    <h1>Teknisi Internal</h1>
    <p>Daftar teknisi yang dapat ditugaskan menangani perbaikan.</p>
</div>

<div class="grid-2">
    <div class="table-card">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Nama</th><th>Keahlian</th><th>Kontak</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($rows === []): ?>
                        <tr><td colspan="4" class="empty-note">Belum ada teknisi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= e($row['name']); ?></td>
                                <td><?= e($row['specialization'] ?? '-'); ?></td>
                                <td><?= e($row['phone'] ?? '-'); ?></td>
                                <td>
                                    <form method="POST" action="<?= admin_url('/teknisi/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus teknisi ini?">
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
        <h3 class="card-title">Tambah Teknisi</h3>
        <form method="POST" action="<?= admin_url('/teknisi'); ?>">
            <?= csrf_field(); ?>
            <label for="name">Nama *</label>
            <input id="name" name="name" required>
            <label for="employee_code">Kode Pegawai</label>
            <input id="employee_code" name="employee_code">
            <label for="specialization">Keahlian</label>
            <input id="specialization" name="specialization" placeholder="Contoh: Listrik, AC, Plumbing">
            <label for="phone">Kontak</label>
            <input id="phone" name="phone">
            <div class="form-actions">
                <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>