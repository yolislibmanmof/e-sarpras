<div class="page-head">
    <h1>Stok Gudang</h1>
    <p>Kartu stok barang habis pakai dan perlengkapan Sarpras.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/stok'); ?>" class="toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari kode atau nama...">
            <select name="category">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id']; ?>" <?= (string) $category === (string) $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
        <div class="row-actions">
            <a class="btn btn-secondary btn-sm" href="<?= admin_url('/stok-kategori'); ?>">Kategori</a>
            <?php if (can('stock.create')): ?>
                <a class="btn btn-primary btn-sm" href="<?= admin_url('/stok/tambah'); ?>">+ Tambah Stok</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Stok</th><th>Minimum</th><th>Lokasi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="empty-note">Belum ada data stok.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['item_code']); ?></td>
                            <td><?= e($row['item_name']); ?></td>
                            <td><?= e($row['category_name'] ?? '-'); ?></td>
                            <td>
                                <?php if ((float) $row['quantity'] <= (float) $row['minimum_quantity']): ?>
                                    <span class="badge badge-danger"><?= (float) $row['quantity']; ?> <?= e($row['unit'] ?? ''); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= (float) $row['quantity']; ?> <?= e($row['unit'] ?? ''); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= (float) $row['minimum_quantity']; ?></td>
                            <td><?= e($row['location'] ?? '-'); ?></td>
                            <td>
                                <div class="row-actions">
                                    <?php if (can('stock.update')): ?>
                                        <a class="btn btn-secondary btn-sm" href="<?= admin_url('/stok/' . $row['id'] . '/ubah'); ?>">Ubah</a>
                                    <?php endif; ?>
                                    <?php if (can('stock.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/stok/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus stok ini?">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
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