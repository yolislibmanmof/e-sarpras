<div class="page-head">
    <h1>Pemeliharaan Berkala</h1>
    <p>Jadwal preventive maintenance fasilitas kampus.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/pemeliharaan'); ?>" class="toolbar-form">
            <select name="status">
                <option value="">Semua Status</option>
                <?php foreach (['Terjadwal', 'Selesai'] as $option): ?>
                    <option value="<?= e($option); ?>" <?= $status === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
        <div class="row-actions">
            <a class="btn btn-secondary btn-sm" href="<?= admin_url('/pemeliharaan/log'); ?>">Log Pemeliharaan</a>
            <?php if (can('maintenance.create')): ?>
                <a class="btn btn-primary btn-sm" href="<?= admin_url('/pemeliharaan/tambah'); ?>">+ Tambah Jadwal</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Jadwal</th><th>Kegiatan</th><th>Objek</th><th>Frekuensi</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="empty-note">Belum ada jadwal.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e(format_tanggal($row['schedule_date'])); ?></td>
                            <td><?= e($row['title']); ?><br><small><?= e($row['category']); ?></small></td>
                            <td><?= e($row['asset_name'] ?? $row['room_name'] ?? '-'); ?></td>
                            <td><?= e($row['frequency'] ?? '-'); ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['status'])); ?>"><?= e($row['status']); ?></span></td>
                            <td>
                                <div class="row-actions">
                                    <?php if ($row['status'] !== 'Selesai' && can('maintenance.update')): ?>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('complete-<?= (int) $row['id']; ?>').style.display='block'">Selesaikan</button>
                                    <?php endif; ?>
                                    <?php if (can('maintenance.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/pemeliharaan/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus jadwal ini?">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <?php if ($row['status'] !== 'Selesai' && can('maintenance.update')): ?>
                                    <form id="complete-<?= (int) $row['id']; ?>" method="POST" action="<?= admin_url('/pemeliharaan/' . $row['id'] . '/selesai'); ?>" style="display:none; margin-top:.6rem;">
                                        <?= csrf_field(); ?>
                                        <label>Biaya</label>
                                        <input type="number" step="0.01" name="cost" value="0">
                                        <label>Catatan</label>
                                        <textarea name="notes" rows="2"></textarea>
                                        <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan Penyelesaian</button></div>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>