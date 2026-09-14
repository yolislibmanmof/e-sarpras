<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $isEdit = $building !== null; ?>
<?php $current = $isEdit ? $building + $old : $old; ?>

<div class="page-head">
    <h1><?= $isEdit ? 'Ubah Gedung' : 'Tambah Gedung'; ?></h1>
    <p>Lengkapi data gedung sesuai kondisi sebenarnya.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= $isEdit ? admin_url('/gedung/' . $building['id'] . '/ubah') : admin_url('/gedung'); ?>" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div class="form-grid">
            <div>
                <label for="code">Kode Gedung *</label>
                <input id="code" name="code" value="<?= e($current['code'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="name">Nama Gedung *</label>
                <input id="name" name="name" value="<?= e($current['name'] ?? ''); ?>" required>
            </div>

            <div class="form-full">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" rows="2"><?= e($current['address'] ?? ''); ?></textarea>
            </div>

            <div>
                <label for="ownership_status">Status Kepemilikan</label>
                <select id="ownership_status" name="ownership_status">
                    <?php foreach (['Milik Sendiri', 'Hak Pakai', 'Sewa', 'Lainnya'] as $option): ?>
                        <option value="<?= e($option); ?>" <?= ($current['ownership_status'] ?? '') === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="year_built">Tahun Pembangunan</label>
                <input id="year_built" name="year_built" type="number" value="<?= e($current['year_built'] ?? ''); ?>">
            </div>

            <div>
                <label for="land_area">Luas Lahan (m2)</label>
                <input id="land_area" name="land_area" type="number" step="0.01" value="<?= e($current['land_area'] ?? ''); ?>">
            </div>
            <div>
                <label for="building_area">Luas Bangunan (m2)</label>
                <input id="building_area" name="building_area" type="number" step="0.01" value="<?= e($current['building_area'] ?? ''); ?>">
            </div>

            <div>
                <label for="condition">Kondisi</label>
                <select id="condition" name="condition">
                    <?php foreach (['Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'] as $option): ?>
                        <option value="<?= e($option); ?>" <?= ($current['condition'] ?? 'Baik') === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Fasilitas Aksesibilitas</label>
                <div class="checkbox-row"><input type="checkbox" id="is_disability_friendly" name="is_disability_friendly" <?= (int) ($current['is_disability_friendly'] ?? 0) === 1 ? 'checked' : ''; ?>><label for="is_disability_friendly">Ramah Disabilitas</label></div>
                <div class="checkbox-row"><input type="checkbox" id="has_ramp" name="has_ramp" <?= (int) ($current['has_ramp'] ?? 0) === 1 ? 'checked' : ''; ?>><label for="has_ramp">Jalur Landai (Ramp)</label></div>
                <div class="checkbox-row"><input type="checkbox" id="has_disability_toilet" name="has_disability_toilet" <?= (int) ($current['has_disability_toilet'] ?? 0) === 1 ? 'checked' : ''; ?>><label for="has_disability_toilet">Toilet Disabilitas</label></div>
                <div class="checkbox-row"><input type="checkbox" id="has_lift" name="has_lift" <?= (int) ($current['has_lift'] ?? 0) === 1 ? 'checked' : ''; ?>><label for="has_lift">Lift</label></div>
                <div class="checkbox-row"><input type="checkbox" id="has_guide_path" name="has_guide_path" <?= (int) ($current['has_guide_path'] ?? 0) === 1 ? 'checked' : ''; ?>><label for="has_guide_path">Jalur Pemandu</label></div>
            </div>

            <div class="form-full">
                <label for="photo">Foto Gedung</label>
                <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp">
                <?php if (!empty($current['photo'])): ?>
                    <img src="<?= base_url('/media/' . $current['photo']); ?>" alt="Foto gedung" style="width:100%;max-height:240px;object-fit:cover;border-radius:12px;margin-top:.8rem;box-shadow:var(--shadow);">
                <?php endif; ?>
                <p class="file-note">Format JPG/PNG/WEBP maks 2MB. Foto akan tampil di laman publik.</p>
            </div>

            <div class="form-full">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" rows="2"><?= e($current['notes'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="<?= admin_url('/gedung'); ?>">Kembali</a>
        </div>
    </form>
</div>

<?php if ($isEdit): ?>
<div class="form-card" style="margin-top:1.4rem;">
    <h3 class="card-title">Lantai pada Gedung Ini</h3>

    <?php if ($floors === []): ?>
        <p class="empty-note">Belum ada lantai tercatat.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr><th>Level</th><th>Nama</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($floors as $floor): ?>
                    <tr>
                        <td><?= (int) $floor['level']; ?></td>
                        <td><?= e($floor['name']); ?></td>
                        <td>
                            <?php if (can('floor.delete')): ?>
                                <form method="POST" action="<?= admin_url('/lantai/' . $floor['id'] . '/hapus'); ?>" data-confirm="Yakin ingin menghapus lantai ini?">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (can('floor.create')): ?>
        <h4 class="sub-title">Tambah Lantai</h4>
        <form method="POST" action="<?= admin_url('/gedung/' . $building['id'] . '/lantai'); ?>">
            <?= csrf_field(); ?>
            <div class="form-grid">
                <div>
                    <label for="level">Nomor Lantai *</label>
                    <input id="level" name="level" type="number" required>
                </div>
                <div>
                    <label for="floor_name">Nama Lantai *</label>
                    <input id="floor_name" name="name" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-sm">Tambah Lantai</button>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php endif; ?>