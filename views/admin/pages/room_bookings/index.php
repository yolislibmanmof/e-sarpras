<div class="page-head">
    <h1>Peminjaman Ruangan</h1>
    <p>Daftar pengajuan peminjaman ruangan dan jadwal kegiatan.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/peminjaman-ruangan'); ?>" class="toolbar-form">
            <select name="status">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu Verifikasi', 'Diverifikasi', 'Disetujui', 'Ditolak'] as $option): ?>
                    <option value="<?= e($option); ?>" <?= $status === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Kode</th><th>Kegiatan</th><th>Ruangan</th><th>Waktu</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="empty-note">Belum ada pengajuan.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['booking_code']); ?></td>
                            <td><?= e($row['activity_name']); ?></td>
                            <td><?= e($row['room_name'] ?? '-'); ?></td>
                            <td><?= e(format_tanggal_waktu($row['start_at'])); ?> - <?= e(date('H:i', strtotime($row['end_at']))); ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['status'])); ?>"><?= e($row['status']); ?></span></td>
                            <td><a class="btn btn-secondary btn-sm" href="<?= admin_url('/peminjaman-ruangan/' . $row['id']); ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>