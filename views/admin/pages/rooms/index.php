<div class="page-head">
    <h1>Master Ruangan</h1>
    <p>Daftar seluruh ruangan beserta jenis, kapasitas, dan kondisinya.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/ruangan'); ?>" class="table-search toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari kode atau nama ruangan...">
            <select name="building">
                <option value="">Semua Gedung</option>
                <?php foreach ($buildings as $b): ?>
                    <option value="<?= (int) $b['id']; ?>" <?= (string) $building === (string) $b['id'] ? 'selected' : ''; ?>><?= e($b['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
        <?php if (can('room.create')): ?>
            <a class="btn btn-primary btn-sm" href="<?= admin_url('/ruangan/tambah'); ?>">+ Tambah Ruangan</a>
        <?php endif; ?>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Ruangan</th>
                    <th>Gedung</th>
                    <th>Jenis</th>
                    <th>Kapasitas</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="empty-note">Belum ada data ruangan.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['code']); ?></td>
                            <td><?= e($row['name']); ?></td>
                            <td><?= e($row['building_name'] ?? '-'); ?></td>
                            <td><?= e($row['room_type']); ?></td>
                            <td><?= (int) $row['capacity']; ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['condition'])); ?>"><?= e($row['condition']); ?></span></td>
                            <td>
                                <div class="row-actions">
                                    <?php if (can('room.update')): ?>
                                        <a class="btn btn-secondary btn-sm" href="<?= admin_url('/ruangan/' . $row['id'] . '/ubah'); ?>">Ubah</a>
                                    <?php endif; ?>
                                    <?php if (can('room.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/ruangan/' . $row['id'] . '/hapus'); ?>" data-confirm="Yakin ingin menghapus ruangan ini?">
                                            <?= csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>