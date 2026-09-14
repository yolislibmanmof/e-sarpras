<div class="page-head">
    <h1>Inventaris Aset</h1>
    <p>Daftar seluruh aset bergerak dan tidak bergerak milik perguruan tinggi.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/aset'); ?>" class="toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari kode, nama, serial...">
            <select name="category">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id']; ?>" <?= (string) $category === (string) $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="building">
                <option value="">Semua Gedung</option>
                <?php foreach ($buildings as $b): ?>
                    <option value="<?= (int) $b['id']; ?>" <?= (string) $building === (string) $b['id'] ? 'selected' : ''; ?>><?= e($b['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="condition">
                <option value="">Semua Kondisi</option>
                <?php foreach (['Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'] as $option): ?>
                    <option value="<?= e($option); ?>" <?= $condition === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
        <div class="row-actions">
            <a class="btn btn-secondary btn-sm" href="<?= admin_url('/aset-kategori'); ?>">Kategori</a>
            <?php if (can('asset.create')): ?>
                <a class="btn btn-primary btn-sm" href="<?= admin_url('/aset/tambah'); ?>">+ Tambah Aset</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="empty-note">Belum ada data aset.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['code']); ?></td>
                            <td><?= e($row['name']); ?></td>
                            <td><?= e($row['category_name'] ?? '-'); ?></td>
                            <td><?= e(trim(($row['building_name'] ?? '-') . ' / ' . ($row['room_name'] ?? '-')), ); ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['condition'])); ?>"><?= e($row['condition']); ?></span></td>
                            <td><?= e($row['status']); ?></td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn btn-secondary btn-sm" href="<?= admin_url('/aset/' . $row['id']); ?>">Detail</a>
                                    <?php if (can('asset.update')): ?>
                                        <a class="btn btn-secondary btn-sm" href="<?= admin_url('/aset/' . $row['id'] . '/ubah'); ?>">Ubah</a>
                                    <?php endif; ?>
                                    <?php if (can('asset.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/aset/' . $row['id'] . '/hapus'); ?>" data-confirm="Yakin ingin menghapus aset ini?">
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