<div class="page-head">
    <h1>Audit Log</h1>
    <p>Riwayat aktivitas sistem untuk keperluan audit dan keamanan.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/audit-log'); ?>" class="toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari aksi atau modul...">
            <input type="text" name="module" value="<?= e($module); ?>" placeholder="Modul">
            <input type="text" name="user" value="<?= e($user); ?>" placeholder="Nama pengguna">
            <input type="date" name="from" value="<?= e($from); ?>">
            <input type="date" name="to" value="<?= e($to); ?>">
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Modul</th>
                    <th>Record ID</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="empty-note">Tidak ada log.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e(format_tanggal_waktu($row['created_at'])); ?></td>
                            <td><?= e($row['user_name'] ?? 'Sistem'); ?></td>
                            <td><?= e($row['action']); ?></td>
                            <td><?= e($row['module']); ?></td>
                            <td><?= e($row['record_id'] ?? '-'); ?></td>
                            <td><?= e($row['ip_address'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>