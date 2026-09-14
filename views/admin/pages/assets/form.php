<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $isEdit = $asset !== null; ?>
<?php $current = $isEdit ? $asset + $old : $old; ?>

<div class="page-head">
    <h1><?= $isEdit ? 'Ubah Aset' : 'Tambah Aset'; ?></h1>
    <p>Catat aset sesuai dokumen pengadaan dan kondisi fisik.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= $isEdit ? admin_url('/aset/' . $asset['id'] . '/ubah') : admin_url('/aset'); ?>">
        <?= csrf_field(); ?>

        <div class="form-grid">
            <div>
                <label for="code">Kode Aset *</label>
                <input id="code" name="code" value="<?= e($current['code'] ?? $suggested); ?>" required>
            </div>
            <div>
                <label for="name">Nama Aset *</label>
                <input id="name" name="name" value="<?= e($current['name'] ?? ''); ?>" required>
            </div>

            <div>
                <label for="asset_category_id">Kategori *</label>
                <select id="asset_category_id" name="asset_category_id" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int) $c['id']; ?>" <?= (string) ($current['asset_category_id'] ?? '') === (string) $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="serial_number">Nomor Seri</label>
                <input id="serial_number" name="serial_number" value="<?= e($current['serial_number'] ?? ''); ?>">
            </div>

            <div>
                <label for="brand">Merek</label>
                <input id="brand" name="brand" value="<?= e($current['brand'] ?? ''); ?>">
            </div>
            <div>
                <label for="model">Model / Tipe</label>
                <input id="model" name="model" value="<?= e($current['model'] ?? ''); ?>">
            </div>

            <div>
                <label for="building_id">Gedung</label>
                <select id="building_id" name="building_id">
                    <option value="">Tanpa gedung</option>
                    <?php foreach ($buildings as $b): ?>
                        <option value="<?= (int) $b['id']; ?>" <?= (string) ($current['building_id'] ?? '') === (string) $b['id'] ? 'selected' : ''; ?>><?= e($b['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="room_id">Ruangan</label>
                <select id="room_id" name="room_id">
                    <option value="">Tanpa ruangan</option>
                    <?php foreach ($groupedRooms as $buildingId => $roomList): ?>
                        <optgroup label="Gedung ID <?= (int) $buildingId; ?>">
                            <?php foreach ($roomList as $room): ?>
                                <option value="<?= (int) $room['id']; ?>" <?= (string) ($current['room_id'] ?? '') === (string) $room['id'] ? 'selected' : ''; ?>><?= e($room['code'] . ' - ' . $room['name']); ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="acquisition_date">Tanggal Pengadaan</label>
                <input id="acquisition_date" name="acquisition_date" type="date" value="<?= e($current['acquisition_date'] ?? ''); ?>">
            </div>
            <div>
                <label for="acquisition_value">Nilai Perolehan (Rp)</label>
                <input id="acquisition_value" name="acquisition_value" type="number" step="0.01" value="<?= e($current['acquisition_value'] ?? 0); ?>">
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
                <label for="status">Status</label>
                <select id="status" name="status">
                    <?php foreach (['Aktif', 'Tidak Aktif', 'Dalam Perbaikan', 'Dipinjamkan', 'Dihapuskan'] as $option): ?>
                        <option value="<?= e($option); ?>" <?= ($current['status'] ?? 'Aktif') === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="ownership">Kepemilikan</label>
                <input id="ownership" name="ownership" value="<?= e($current['ownership'] ?? ''); ?>" placeholder="Contoh: Milik Universitas">
            </div>
            <div>
                <label for="warranty_expiry">Berakhir Garansi</label>
                <input id="warranty_expiry" name="warranty_expiry" type="date" value="<?= e($current['warranty_expiry'] ?? ''); ?>">
            </div>

            <div class="checkbox-row" style="align-self:end;">
                <input type="checkbox" id="is_borrowable" name="is_borrowable" <?= (int) ($current['is_borrowable'] ?? 1) === 1 ? 'checked' : ''; ?>>
                <label for="is_borrowable">Dapat Dipinjamkan</label>
            </div>

            <div class="form-full">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" rows="2"><?= e($current['notes'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="<?= admin_url('/aset'); ?>">Kembali</a>
        </div>
    </form>
</div>