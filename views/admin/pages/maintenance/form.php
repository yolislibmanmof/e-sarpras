<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>

<div class="page-head">
    <h1>Tambah Jadwal Pemeliharaan</h1>
    <p>Buat jadwal servis rutin fasilitas.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= admin_url('/pemeliharaan'); ?>">
        <?= csrf_field(); ?>

        <div class="form-grid">
            <div class="form-full">
                <label for="title">Nama Kegiatan *</label>
                <input id="title" name="title" value="<?= e($old['title'] ?? ''); ?>" placeholder="Contoh: Servis AC berkala" required>
            </div>

            <div>
                <label for="category">Kategori</label>
                <select id="category" name="category">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= e($category); ?>" <?= ($old['category'] ?? 'Lainnya') === $category ? 'selected' : ''; ?>><?= e($category); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="frequency">Frekuensi</label>
                <select id="frequency" name="frequency">
                    <?php foreach ($frequencies as $frequency): ?>
                        <option value="<?= e($frequency); ?>" <?= ($old['frequency'] ?? 'Bulanan') === $frequency ? 'selected' : ''; ?>><?= e($frequency); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="schedule_date">Tanggal Jadwal *</label>
                <input id="schedule_date" name="schedule_date" type="date" value="<?= e($old['schedule_date'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="asset_id">Aset</label>
                <select id="asset_id" name="asset_id">
                    <option value="">Tidak spesifik</option>
                    <?php foreach ($assets as $asset): ?>
                        <option value="<?= (int) $asset['id']; ?>"><?= e($asset['code'] . ' - ' . $asset['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-full">
                <label for="room_id">Ruangan</label>
                <select id="room_id" name="room_id">
                    <option value="">Tidak spesifik</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= (int) $room['id']; ?>"><?= e($room['code'] . ' - ' . $room['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-full">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="2"><?= e($old['description'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            <a class="btn btn-secondary" href="<?= admin_url('/pemeliharaan'); ?>">Kembali</a>
        </div>
    </form>
</div>