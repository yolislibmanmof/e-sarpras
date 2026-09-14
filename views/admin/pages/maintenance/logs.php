<div class="page-head">
    <h1>Log Pemeliharaan</h1>
    <p>Riwayat pelaksanaan pemeliharaan dan perbaikan.</p>
</div>

<div class="table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Tanggal</th><th>Objek</th><th>Petugas</th><th>Biaya</th><th>Catatan</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="5" class="empty-note">Belum ada log.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e(format_tanggal($row['action_date'])); ?></td>
                            <td><?= e($row['asset_name'] ?? $row['room_name'] ?? '-'); ?></td>
                            <td><?= e($row['technician_name'] ?? $row['vendor_name'] ?? '-'); ?></td>
                            <td><?= e(format_rupiah($row['cost'])); ?></td>
                            <td><?= e(str_limit($row['notes'] ?? '-', 60)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>