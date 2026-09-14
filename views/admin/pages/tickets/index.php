<div class="page-head">
    <h1>Tiket Kerusakan</h1>
    <p>Daftar laporan kerusakan dari seluruh unit kampus.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/tiket'); ?>" class="toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari kode atau judul...">
            <select name="status">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu Verifikasi', 'Diverifikasi', 'Sedang Diperbaiki', 'Menunggu Sparepart', 'Selesai', 'Ditolak'] as $option): ?>
                    <option value="<?= e($option); ?>" <?= $status === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="priority">
                <option value="">Semua Urgensi</option>
                <?php foreach (['Normal', 'Mendesak', 'Darurat'] as $option): ?>
                    <option value="<?= e($option); ?>" <?= $priority === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Lokasi</th>
                    <th>Urgensi</th>
                    <th>Status</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="empty-note">Belum ada tiket.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['ticket_code']); ?></td>
                            <td><?= e(str_limit($row['title'], 45)); ?></td>
                            <td><?= e(trim(($row['building_name'] ?? '-') . ' / ' . ($row['room_name'] ?? '-'))); ?></td>
                            <td>
                                <?php $pClass = $row['priority'] === 'Darurat' ? 'badge-danger' : ($row['priority'] === 'Mendesak' ? 'badge-warning' : 'badge-info'); ?>
                                <span class="badge <?= $pClass; ?>"><?= e($row['priority']); ?></span>
                            </td>
                            <td><span class="badge <?= e(status_badge_class($row['status'])); ?>"><?= e($row['status']); ?></span></td>
                            <td><?= e($row['technician_name'] ?? $row['vendor_name'] ?? '-'); ?></td>
                            <td>
                                <a class="btn btn-secondary btn-sm" href="<?= admin_url('/tiket/' . $row['id']); ?>">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>