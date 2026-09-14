<div class="page-head">
    <h1>Kategori Aset</h1>
    <p>Pengelompokan aset untuk kebutuhan inventarisasi dan pelaporan.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="grid-2">
    <div class="table-card">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>Kode</th><th>Nama</th><th>Jumlah Aset</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['code']); ?></td>
                            <td><?= e($row['name']); ?></td>
                            <td><?= (int) $row['asset_count']; ?></td>
                            <td>
                                <?php if (can('asset.delete')): ?>
                                    <form method="POST" action="<?= admin_url('/aset-kategori/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus kategori ini?">
                                        <?= csrf_field(); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Tambah Kategori</h3>
        <?php if (can('asset.create')): ?>
            <form method="POST" action="<?= admin_url('/aset-kategori'); ?>">
                <?= csrf_field(); ?>
                <label for="code">Kode *</label>
                <input id="code" name="code" required>
                <label for="name">Nama *</label>
                <input id="name" name="name" required>
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="2"></textarea>
                <div class="form-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
                </div>
            </form>
        <?php else: ?>
            <p class="empty-note">Anda tidak memiliki izin untuk menambah kategori.</p>
        <?php endif; ?>
    </div>
</div>