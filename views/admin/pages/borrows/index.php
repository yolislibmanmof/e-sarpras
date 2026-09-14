<div class="page-head">
    <h1>Peminjaman Barang</h1>
    <p>Daftar pengajuan peminjaman barang inventaris.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/peminjaman-barang'); ?>" class="toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari kode atau nama...">
            <select name="status">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu Verifikasi', 'Diverifikasi', 'Dipinjam', 'Selesai', 'Ditolak'] as $option): ?>
                    <option value="<?= e($option); ?>" <?= $status === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Kode</th><th>Peminjam</th><th>Tanggal Pinjam</th><th>Status</th><th>Persetujuan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="empty-note">Belum ada pengajuan.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['borrow_code']); ?></td>
                            <td><?= e($row['borrower_name']); ?> (<?= e($row['borrower_type']); ?>)</td>
                            <td><?= e(format_tanggal($row['borrow_date'])); ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['status'])); ?>"><?= e($row['status']); ?></span></td>
                            <td><?= e($row['approval_status']); ?></td>
                            <td><a class="btn btn-secondary btn-sm" href="<?= admin_url('/peminjaman-barang/' . $row['id']); ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>