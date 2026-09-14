<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $isEdit = $room !== null; ?>
<?php $current = $isEdit ? $room + $old : $old; ?>
<?php $roomTypes = ['Ruang Kelas', 'Laboratorium', 'Studio', 'Bengkel Kerja', 'Perpustakaan', 'Ruang Dosen', 'Ruang Administrasi', 'Ruang Pimpinan', 'Auditorium', 'Mushola', 'Kantin', 'Ruang UKM', 'Toilet', 'Ruang Server', 'Gudang', 'Lainnya']; ?>

<div class="page-head">
    <h1><?= $isEdit ? 'Ubah Ruangan' : 'Tambah Ruangan'; ?></h1>
    <p>Lengkapi data ruangan sesuai kondisi sebenarnya.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= $isEdit ? admin_url('/ruangan/' . $room['id'] . '/ubah') : admin_url('/ruangan'); ?>" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div class="form-grid">
            <div>
                <label for="code">Kode Ruangan *</label>
                <input id="code" name="code" value="<?= e($current['code'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="name">Nama Ruangan *</label>
                <input id="name" name="name" value="<?= e($current['name'] ?? ''); ?>" required>
            </div>

            <div>
                <label for="building_id">Gedung *</label>
                <select id="building_id" name="building_id" required>
                    <option value="">Pilih gedung</option>
                    <?php foreach ($buildings as $b): ?>
                        <option value="<?= (int) $b['id']; ?>" <?= (string) ($current['building_id'] ?? '') === (string) $b['id'] ? 'selected' : ''; ?>><?= e($b['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="floor_id">Lantai</label>
                <select id="floor_id" name="floor_id">
                    <option value="">Tanpa lantai</option>
                    <?php foreach ($floors as $buildingId => $floorList): ?>
                        <optgroup label="Gedung ID <?= (int) $buildingId; ?>">
                            <?php foreach ($floorList as $floor): ?>
                                <option value="<?= (int) $floor['id']; ?>" <?= (string) ($current['floor_id'] ?? '') === (string) $floor['id'] ? 'selected' : ''; ?>>Lantai <?= (int) $floor['level']; ?> - <?= e($floor['name']); ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="room_type">Jenis Ruangan</label>
                <select id="room_type" name="room_type">
                    <?php foreach ($roomTypes as $option): ?>
                        <option value="<?= e($option); ?>" <?= ($current['room_type'] ?? 'Ruang Kelas') === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="capacity">Kapasitas (orang)</label>
                <input id="capacity" name="capacity" type="number" value="<?= e($current['capacity'] ?? 0); ?>">
            </div>

            <div>
                <label for="area">Luas (m2)</label>
                <input id="area" name="area" type="number" step="0.01" value="<?= e($current['area'] ?? ''); ?>">
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
                <label for="status">Status Penggunaan</label>
                <select id="status" name="status">
                    <?php foreach (['Aktif', 'Tidak Aktif', 'Dalam Perbaikan'] as $option): ?>
                        <option value="<?= e($option); ?>" <?= ($current['status'] ?? 'Aktif') === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="checkbox-row" style="align-self:end;">
                <input type="checkbox" id="is_disability_friendly" name="is_disability_friendly" <?= (int) ($current['is_disability_friendly'] ?? 0) === 1 ? 'checked' : ''; ?>>
                <label for="is_disability_friendly">Ramah Disabilitas</label>
            </div>

            <div class="form-full">
                <label for="facilities">Fasilitas Ruangan</label>
                <textarea id="facilities" name="facilities" rows="2" placeholder="Contoh: AC, proyektor, 40 kursi, whiteboard"><?= e($current['facilities'] ?? ''); ?></textarea>
            </div>

            <div class="form-full">
                <label for="photo">Foto Ruangan</label>
                <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp">
                <?php if (!empty($current['photo'])): ?>
                    <img src="<?= base_url('/media/' . $current['photo']); ?>" alt="Foto ruangan" style="width:100%;max-height:240px;object-fit:cover;border-radius:12px;margin-top:.8rem;box-shadow:var(--shadow);">
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
            <a class="btn btn-secondary" href="<?= admin_url('/ruangan'); ?>">Kembali</a>
        </div>
    </form>
</div>