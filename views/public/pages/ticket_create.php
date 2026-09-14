<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $errors = \App\Core\Session::getFlash('errors') ?? []; ?>
<?php $currentCategory = $old['category'] ?? $preCategory ?? ''; ?>
<?php $live = \App\Core\Database::instance()->select("SELECT title, status FROM tickets ORDER BY id DESC LIMIT 5"); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Lapor Kerusakan</span>
        <h1>Lapor Kerusakan Fasilitas</h1>
        <p>Sampaikan laporan kerusakan sarana atau prasarana kampus. Tim Sarpras akan menindaklanjuti dan Anda dapat memantau statusnya.</p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="stat-card shine"><span class="stat-value"><?= (int) $stats['selesai']; ?></span><span class="stat-label">Laporan Selesai</span></div>
            <div class="stat-card shine"><span class="stat-value"><?= (int) $stats['proses']; ?></span><span class="stat-label">Sedang Ditangani</span></div>
            <div class="stat-card shine"><span class="stat-value"><?= (int) $stats['menunggu']; ?></span><span class="stat-label">Menunggu Verifikasi</span></div>
        </div>
    </div>
</section>

<section class="section page-body">
    <div class="container split-grid">
        <div>
            <?php if ($errors !== []): ?>
                <div class="public-alert public-alert-error">
                    <ul>
                        <?php foreach ($errors as $list): foreach ($list as $m): ?>
                            <li><?= e($m); ?></li>
                        <?php endforeach; endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1.2rem;">
                <?php foreach ($categories as $category): ?>
                    <a class="badge <?= $currentCategory === $category ? 'badge-success' : 'badge-info'; ?>" href="<?= base_url('/lapor-kerusakan?kategori=' . urlencode($category)); ?>"><?= e($category); ?></a>
                <?php endforeach; ?>
            </div>

            <div class="public-form-card shine">
                <form method="POST" action="<?= base_url('/lapor-kerusakan'); ?>" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="form-grid">
                        <div><label for="reporter_name">Nama Pelapor *</label><input id="reporter_name" name="reporter_name" value="<?= e($old['reporter_name'] ?? ''); ?>" required></div>
                        <div>
                            <label for="reporter_type">Status</label>
                            <select id="reporter_type" name="reporter_type">
                                <?php foreach ($reporterTypes as $type): ?>
                                    <option value="<?= e($type); ?>" <?= ($old['reporter_type'] ?? 'Mahasiswa') === $type ? 'selected' : ''; ?>><?= e($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div><label for="reporter_contact">Email / No. HP *</label><input id="reporter_contact" name="reporter_contact" value="<?= e($old['reporter_contact'] ?? ''); ?>" required></div>
                        <div><label for="incident_date">Tanggal Kejadian</label><input id="incident_date" name="incident_date" type="date" value="<?= e($old['incident_date'] ?? ''); ?>"></div>
                        <div>
                            <label for="building_id">Gedung *</label>
                            <select id="building_id" name="building_id" required>
                                <option value="">Pilih gedung</option>
                                <?php foreach ($buildings as $b): ?>
                                    <option value="<?= (int) $b['id']; ?>" <?= (string) ($old['building_id'] ?? '') === (string) $b['id'] ? 'selected' : ''; ?>><?= e($b['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="room_id">Ruangan</label>
                            <select id="room_id" name="room_id">
                                <option value="">Tidak spesifik</option>
                                <?php foreach ($groupedRooms as $buildingId => $roomList): ?>
                                    <optgroup label="Gedung ID <?= (int) $buildingId; ?>">
                                        <?php foreach ($roomList as $room): ?>
                                            <option value="<?= (int) $room['id']; ?>" <?= (string) ($old['room_id'] ?? '') === (string) $room['id'] ? 'selected' : ''; ?>><?= e($room['code'] . ' - ' . $room['name']); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="asset_id">Aset Terkait (opsional)</label>
                            <select id="asset_id" name="asset_id">
                                <option value="">Tidak ada</option>
                                <?php foreach ($groupedAssets as $buildingId => $assetList): ?>
                                    <optgroup label="Gedung ID <?= (int) $buildingId; ?>">
                                        <?php foreach ($assetList as $asset): ?>
                                            <option value="<?= (int) $asset['id']; ?>" <?= (string) ($old['asset_id'] ?? '') === (string) $asset['id'] ? 'selected' : ''; ?>><?= e($asset['code'] . ' - ' . $asset['name']); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="category">Kategori Kerusakan *</label>
                            <select id="category" name="category" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= e($category); ?>" <?= $currentCategory === $category ? 'selected' : ''; ?>><?= e($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="priority">Tingkat Urgensi</label>
                            <select id="priority" name="priority">
                                <?php foreach ($priorities as $priority): ?>
                                    <option value="<?= e($priority); ?>" <?= ($old['priority'] ?? 'Normal') === $priority ? 'selected' : ''; ?>><?= e($priority); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div><label for="title">Judul Laporan *</label><input id="title" name="title" value="<?= e($old['title'] ?? ''); ?>" required></div>
                        <div class="form-full"><label for="description">Deskripsi Kerusakan *</label><textarea id="description" name="description" rows="4" required><?= e($old['description'] ?? ''); ?></textarea></div>
                        <div class="form-full">
                            <label for="photo">Foto Bukti (opsional, maks 2MB)</label>
                            <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp">
                            <p class="file-note" id="photoName"></p>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>

        <aside class="side-panel">
            <div class="card info-card shine">
                <h4><span class="live-dot"></span> Laporan Terbaru</h4>
                <?php if ($live === []): ?>
                    <p>Belum ada laporan masuk.</p>
                <?php else: ?>
                    <ul class="mini-steps">
                        <?php foreach ($live as $t): ?>
                            <li><b>&#9632;</b> <?= e(str_limit($t['title'], 34)); ?><br><span class="badge <?= e(status_badge_class($t['status'])); ?>"><?= e($t['status']); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="card info-card">
                <h4>Alur Penanganan</h4>
                <ul class="mini-steps">
                    <li><b>1</b> Laporan diterima, kode tiket diterbitkan.</li>
                    <li><b>2</b> Verifikasi oleh tim Sarpras (maks. 1x24 jam kerja).</li>
                    <li><b>3</b> Penugasan teknisi / vendor dan perbaikan.</li>
                    <li><b>4</b> Selesai — status dapat dipantau daring.</li>
                </ul>
            </div>
            <div class="card info-card">
                <h4>Kontak Darurat</h4>
                <p>Telp: <?= e(setting_value('campus_phone', '-')); ?><br>Email: <?= e(setting_value('campus_email', '-')); ?></p>
            </div>
        </aside>
    </div>
</section>