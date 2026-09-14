<div class="page-head">
    <h1>Permintaan Barang</h1>
    <p>Daftar permintaan ATK dan barang elektronik dari seluruh unit.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/permintaan-barang'); ?>" class="toolbar-form">
            <select name="status">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu Verifikasi', 'Diverifikasi', 'Diserahkan', 'Ditolak'] as $option): ?>
                    <option value="<?= e($option); ?>" <?= $status === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Kode</th><th>Pemohon</th><th>Jenis</th><th>Dibutuhkan</th><th>Status</th><th>Persetujuan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="empty-note">Belum ada permintaan.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['request_code']); ?></td>
                            <td><?= e($row['requester_name']); ?> (<?= e($row['unit_name']); ?>)</td>
                            <td><?= e($row['request_type']); ?></td>
                            <td><?= e(format_tanggal($row['needed_date'])); ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['status'])); ?>"><?= e($row['status']); ?></span></td>
                            <td><?= e($row['approval_status']); ?></td>
                            <td><a class="btn btn-secondary btn-sm" href="<?= admin_url('/permintaan-barang/' . $row['id']); ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>