<div class="page-head">
    <h1><?= e($asset['name']); ?></h1>
    <p><?= e($asset['code']); ?> &middot; <?= e($asset['category_name'] ?? '-'); ?></p>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Informasi Aset</h3>
        <table class="detail-table">
            <tr><th>Kode</th><td><?= e($asset['code']); ?></td></tr>
            <tr><th>Nama</th><td><?= e($asset['name']); ?></td></tr>
            <tr><th>Merek / Model</th><td><?= e(trim(($asset['brand'] ?? '-') . ' / ' . ($asset['model'] ?? '-'))); ?></td></tr>
            <tr><th>Nomor Seri</th><td><?= e($asset['serial_number'] ?? '-'); ?></td></tr>
            <tr><th>Lokasi</th><td><?= e(trim(($asset['building_name'] ?? '-') . ' / ' . ($asset['room_name'] ?? '-'))); ?></td></tr>
            <tr><th>Tanggal Pengadaan</th><td><?= e(format_tanggal($asset['acquisition_date'])); ?></td></tr>
            <tr><th>Nilai Perolehan</th><td><?= e(format_rupiah($asset['acquisition_value'])); ?></td></tr>
            <tr><th>Kondisi</th><td><span class="badge <?= e(status_badge_class($asset['condition'])); ?>"><?= e($asset['condition']); ?></span></td></tr>
            <tr><th>Status</th><td><?= e($asset['status']); ?></td></tr>
            <tr><th>Dapat Dipinjamkan</th><td><?= (int) $asset['is_borrowable'] === 1 ? 'Ya' : 'Tidak'; ?></td></tr>
        </table>
        <div class="form-actions">
            <?php if (can('asset.update')): ?>
                <a class="btn btn-secondary btn-sm" href="<?= admin_url('/aset/' . $asset['id'] . '/ubah'); ?>">Ubah</a>
            <?php endif; ?>
            <a class="btn btn-secondary btn-sm" href="<?= admin_url('/aset'); ?>">Kembali</a>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Label Inventaris</h3>
        <?php if ($labels === []): ?>
            <p class="empty-note">Belum ada label dibuat.</p>
        <?php else: ?>
            <ul class="list-plain">
                <?php foreach ($labels as $label): ?>
                    <li>
                        <span><?= e($label['label_code']); ?></span>
                        <span class="row-actions">
                            <?php if (can('asset.label')): ?>
                                <form method="POST" action="<?= admin_url('/label/' . $label['id'] . '/hapus'); ?>" data-confirm="Hapus label ini?">
                                    <?= csrf_field(); ?>
                                    <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                </form>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (can('asset.label')): ?>
            <div class="form-actions">
                <form method="POST" action="<?= admin_url('/aset/' . $asset['id'] . '/label'); ?>">
                    <?= csrf_field(); ?>
                    <button class="btn btn-primary btn-sm" type="submit">Buat Label</button>
                </form>
                <?php if ($labels !== []): ?>
                    <a class="btn btn-secondary btn-sm" href="<?= admin_url('/aset/' . $asset['id'] . '/label/cetak'); ?>" target="_blank">Cetak Label</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Perbarui Kondisi</h3>
        <?php if (can('asset.update')): ?>
            <form method="POST" action="<?= admin_url('/aset/' . $asset['id'] . '/kondisi'); ?>">
                <?= csrf_field(); ?>
                <label for="new_condition">Kondisi Baru</label>
                <select id="new_condition" name="new_condition" required>
                    <?php foreach (['Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'] as $option): ?>
                        <option value="<?= e($option); ?>"><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="note">Catatan Perubahan *</label>
                <textarea id="note" name="note" rows="2" required></textarea>
                <div class="form-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Simpan Kondisi</button>
                </div>
            </form>
        <?php endif; ?>

        <h4 class="sub-title">Riwayat Kondisi</h4>
        <?php if ($histories === []): ?>
            <p class="empty-note">Belum ada riwayat kondisi.</p>
        <?php else: ?>
            <ul class="list-plain">
                <?php foreach ($histories as $history): ?>
                    <li>
                        <span><?= e($history['old_condition'] ?? '-'); ?> &rarr; <?= e($history['new_condition']); ?></span>
                        <span><?= e(format_tanggal_waktu($history['created_at'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3 class="card-title">Penghapusan Aset</h3>
        <?php if ($asset['status'] !== 'Dihapuskan' && can('asset.disposal')): ?>
            <form method="POST" action="<?= admin_url('/aset/' . $asset['id'] . '/penghapusan'); ?>">
                <?= csrf_field(); ?>
                <label for="reason">Alasan Penghapusan *</label>
                <textarea id="reason" name="reason" rows="2" required></textarea>
                <div class="form-actions">
                    <button class="btn btn-danger btn-sm" type="submit">Ajukan Penghapusan</button>
                </div>
            </form>
        <?php endif; ?>

        <h4 class="sub-title">Riwayat Usulan</h4>
        <?php if ($disposals === []): ?>
            <p class="empty-note">Belum ada usulan penghapusan.</p>
        <?php else: ?>
            <ul class="list-plain">
                <?php foreach ($disposals as $disposal): ?>
                    <li>
                        <span><?= e(str_limit($disposal['reason'], 50)); ?> &middot; <strong><?= e($disposal['approval_status']); ?></strong></span>
                        <?php if ($disposal['approval_status'] === 'Menunggu' && can('asset.disposal')): ?>
                            <span class="row-actions">
                                <form method="POST" action="<?= admin_url('/penghapusan/' . $disposal['id'] . '/persetujuan'); ?>">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="decision" value="Disetujui">
                                    <button class="btn btn-primary btn-sm" type="submit">Setujui</button>
                                </form>
                                <form method="POST" action="<?= admin_url('/penghapusan/' . $disposal['id'] . '/persetujuan'); ?>">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="decision" value="Ditolak">
                                    <button class="btn btn-danger btn-sm" type="submit">Tolak</button>
                                </form>
                            </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>