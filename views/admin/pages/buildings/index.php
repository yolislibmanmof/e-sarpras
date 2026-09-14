<?php $old = null; ?>
<div class="page-head">
    <h1>Master Gedung</h1>
    <p>Daftar seluruh gedung beserta kondisi dan aksesibilitasnya.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/gedung'); ?>" class="table-search">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari kode atau nama gedung...">
        </form>
        <?php if (can('building.create')): ?>
            <a class="btn btn-primary btn-sm" href="<?= admin_url('/gedung/tambah'); ?>">+ Tambah Gedung</a>
        <?php endif; ?>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Gedung</th>
                    <th>Tahun</th>
                    <th>Kondisi</th>
                    <th>Aksesibilitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="empty-note">Belum ada data gedung.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['code']); ?></td>
                            <td><?= e($row['name']); ?></td>
                            <td><?= e($row['year_built'] ?? '-'); ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['condition'])); ?>"><?= e($row['condition']); ?></span></td>
                            <td>
                                <?php if ((int) $row['is_disability_friendly'] === 1): ?>
                                    <span class="badge badge-success">Ramah Disabilitas</span>
                                <?php else: ?>
                                    <span class="badge badge-info">Standar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <?php if (can('building.update')): ?>
                                        <a class="btn btn-secondary btn-sm" href="<?= admin_url('/gedung/' . $row['id'] . '/ubah'); ?>">Ubah</a>
                                    <?php endif; ?>
                                    <?php if (can('building.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/gedung/' . $row['id'] . '/hapus'); ?>" data-confirm="Yakin ingin menghapus gedung ini?">
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