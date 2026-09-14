<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $errors = \App\Core\Session::getFlash('errors') ?? []; ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/peminjaman'); ?>">Peminjaman</a> &rarr; Ruangan</span>
        <h1>Form Peminjaman Ruangan</h1>
        <p>Periksa ketersediaan ruangan sebelum mengajukan peminjaman.</p>
    </div>
</section>

<?php if (!empty($upcoming)): ?>
<section style="padding:1.6rem 0 0;">
    <div class="container">
        <div class="ticker">
            <div class="ticker-track">
                <?php for ($r = 0; $r < 2; $r++): foreach ($upcoming as $u): ?>
                    <span class="ticker-item">&#128197; <b><?= e($u['activity_name']); ?></b> &middot; <?= e($u['room_name'] ?? '-'); ?> &middot; <?= e(format_tanggal($u['start_at'])); ?></span>
                <?php endforeach; endfor; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section page-body">
    <div class="container split-grid">
        <div>
            <?php if ($errors !== []): ?>
                <div class="public-alert public-alert-error">
                    <ul><?php foreach ($errors as $list): foreach ($list as $m): ?><li><?= e($m); ?></li><?php endforeach; endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <div class="public-form-card shine">
                <form method="POST" action="<?= base_url('/peminjaman/ruangan'); ?>">
                    <?= csrf_field(); ?>
                    <div class="form-grid">
                        <div><label for="borrower_name">Nama Peminjam *</label><input id="borrower_name" name="borrower_name" value="<?= e($old['borrower_name'] ?? ''); ?>" required></div>
                        <div>
                            <label for="borrower_type">Status</label>
                            <select id="borrower_type" name="borrower_type">
                                <?php foreach ($borrowerTypes as $type): ?>
                                    <option value="<?= e($type); ?>" <?= ($old['borrower_type'] ?? 'Dosen') === $type ? 'selected' : ''; ?>><?= e($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div><label for="unit_name">Unit / Prodi / Fakultas</label><input id="unit_name" name="unit_name" value="<?= e($old['unit_name'] ?? ''); ?>"></div>
                        <div><label for="phone">No. HP *</label><input id="phone" name="phone" value="<?= e($old['phone'] ?? ''); ?>" required></div>
                        <div>
                            <label for="room_id">Ruangan *</label>
                            <select id="room_id" name="room_id" required>
                                <option value="">Pilih ruangan</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?= (int) $room['id']; ?>" <?= (string) ($old['room_id'] ?? '') === (string) $room['id'] ? 'selected' : ''; ?>><?= e($room['code'] . ' - ' . $room['name'] . ' (kap. ' . (int) $room['capacity'] . ')'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div><label for="participant_count">Jumlah Peserta</label><input id="participant_count" name="participant_count" type="number" value="<?= e($old['participant_count'] ?? 0); ?>"></div>
                        <div><label for="start_at">Waktu Mulai *</label><input id="start_at" name="start_at" type="datetime-local" value="<?= e($old['start_at'] ?? ''); ?>" required></div>
                        <div><label for="end_at">Waktu Selesai *</label><input id="end_at" name="end_at" type="datetime-local" value="<?= e($old['end_at'] ?? ''); ?>" required></div>
                        <div class="form-full"><label for="activity_name">Nama Kegiatan *</label><input id="activity_name" name="activity_name" value="<?= e($old['activity_name'] ?? ''); ?>" required></div>
                        <div class="form-full"><label for="facilities_needed">Fasilitas yang Dibutuhkan</label><textarea id="facilities_needed" name="facilities_needed" rows="2"><?= e($old['facilities_needed'] ?? ''); ?></textarea></div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        <a class="btn btn-outline" href="<?= base_url('/peminjaman'); ?>">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <aside class="side-panel">
            <div class="card info-card shine">
                <h4><span class="live-dot"></span> Jadwal Terdekat</h4>
                <?php if ($upcoming === []): ?>
                    <p>Belum ada jadwal yang disetujui.</p>
                <?php else: ?>
                    <ul class="mini-steps">
                        <?php foreach ($upcoming as $booking): ?>
                            <li><b>&#9632;</b> <strong><?= e($booking['activity_name']); ?></strong><br><?= e($booking['room_name'] ?? '-'); ?> &middot; <?= e(format_tanggal_waktu($booking['start_at'])); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="card info-card">
                <h4>Alur Persetujuan</h4>
                <ul class="mini-steps">
                    <li><b>1</b> Pengajuan dicek terhadap bentrok jadwal.</li>
                    <li><b>2</b> Verifikasi dan persetujuan Sarpras.</li>
                    <li><b>3</b> Ruangan tercatat pada log utilitas.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>