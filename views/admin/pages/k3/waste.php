<div class="page-head">
    <h1>Log Pengelolaan Limbah</h1>
    <p>Pencatatan penanganan limbah umum dan limbah B3.</p>
</div>

<div class="grid-2">
    <div class="table-card">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Jenis</th><th>Sumber</th><th>Volume</th><th>Tanggal</th><th>Metode</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($rows === []): ?>
                        <tr><td colspan="6" class="empty-note">Belum ada log limbah.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= e($row['waste_type']); ?></td>
                                <td><?= e($row['source'] ?? '-'); ?></td>
                                <td><?= e($row['volume'] ?? '-'); ?></td>
                                <td><?= e(format_tanggal($row['handling_date'])); ?></td>
                                <td><?= e(str_limit($row['handling_method'] ?? '-', 40)); ?></td>
                                <td>
                                    <?php if (can('k3.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/k3/limbah/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus log ini?">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Catat Penanganan Limbah</h3>
        <form method="POST" action="<?= admin_url('/k3/limbah'); ?>">
            <?= csrf_field(); ?>
            <div class="form-grid">
                <div><label for="waste_type">Jenis Limbah *</label><input id="waste_type" name="waste_type" placeholder="Contoh: B3 kimia" required></div>
                <div><label for="source">Sumber</label><input id="source" name="source" placeholder="Contoh: Lab Kimia"></div>
                <div><label for="volume">Volume</label><input id="volume" name="volume"></div>
                <div><label for="handling_date">Tanggal Penanganan</label><input id="handling_date" name="handling_date" type="date"></div>
                <div class="form-full"><label for="handling_method">Metode Penanganan</label><input id="handling_method" name="handling_method"></div>
                <div class="form-full"><label for="vendor_name">Vendor Penanganan</label><input id="vendor_name" name="vendor_name"></div>
            </div>
            <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan</button></div>
        </form>
    </div>
</div>