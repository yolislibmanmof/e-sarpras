<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a> &rarr; <?= e($room['code']); ?></span>
        <h1><?= e($room['name']); ?></h1>
        <p><?= e($room['room_type']); ?> &middot; <?= e($room['building_name'] ?? '-'); ?></p>
    </div>
</section>

<?php if (!empty($room['photo'])): ?>
<section style="padding:1.6rem 0 0;">
    <div class="container">
        <img src="<?= base_url('/media/' . $room['photo']); ?>" alt="<?= e($room['name']); ?>" style="width:100%;max-height:340px;object-fit:cover;border-radius:20px;box-shadow:var(--shadow-lg);">
    </div>
</section>
<?php endif; ?>

<section class="section page-body">
    <div class="container">
        <div class="cards-grid-2">
            <div class="card shine">
                <h3>Informasi Ruangan</h3>
                <ul class="mini-steps" style="margin-top:1rem;">
                    <li><b>&#9632;</b> Kode: <strong><?= e($room['code']); ?></strong></li>
                    <li><b>&#9632;</b> Jenis: <strong><?= e($room['room_type']); ?></strong></li>
                    <li><b>&#9632;</b> Kapasitas: <strong><?= (int) $room['capacity']; ?> orang</strong></li>
                    <li><b>&#9632;</b> Luas: <strong><?= e($room['area'] ?? '-'); ?> m2</strong></li>
                    <li><b>&#9632;</b> Lantai: <strong><?= $floor !== null ? 'L' . (int) $floor['level'] . ' - ' . e($floor['name']) : '-'; ?></strong></li>
                    <li><b>&#9632;</b> Kondisi: <span class="badge <?= e(status_badge_class($room['condition'])); ?>"><?= e($room['condition']); ?></span></li>
                    <li><b>&#9632;</b> Status: <span class="badge badge-info"><?= e($room['status']); ?></span></li>
                </ul>
                <?php if (!empty($room['facilities'])): ?>
                    <h4 style="margin:1rem 0 .5rem; color:var(--primary-dark);">Fasilitas</h4>
                    <p><?= e($room['facilities']); ?></p>
                <?php endif; ?>
            </div>

            <div class="card shine">
                <h3>Lokasi &amp; Aksesibilitas</h3>
                <?php if (!empty($room['building_photo'])): ?>
                    <img src="<?= base_url('/media/' . $room['building_photo']); ?>" alt="<?= e($room['building_name']); ?>" style="width:100%;height:140px;object-fit:cover;border-radius:12px;margin:.8rem 0;">
                <?php endif; ?>
                <p><?= e($room['building_name'] ?? '-'); ?></p>
                <p style="margin-top:.7rem;">
                    <?php if ((int) $room['is_disability_friendly'] === 1): ?>
                        <span class="badge badge-success">Aksesibel / Ramah Disabilitas</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Aksesibilitas dalam peningkatan</span>
                    <?php endif; ?>
                </p>
                <div class="form-actions" style="margin-top:1.2rem;">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('/gedung/' . (int) $room['building_id']); ?>">Lihat Gedung</a>
                    <a class="btn btn-outline btn-sm" href="<?= base_url('/peminjaman/ruangan'); ?>">Ajukan Peminjaman</a>
                </div>
            </div>
        </div>

        <h2 class="section-title" style="margin-top:2.6rem;">Aset di Ruangan Ini</h2>
        <?php if ($assets === []): ?>
            <div class="public-alert public-alert-error">Belum ada aset tercatat pada ruangan ini.</div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($assets as $asset): ?>
                    <div class="card bento shine">
                        <div class="bento-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
                        </div>
                        <h3><?= e($asset['code']); ?></h3>
                        <p><?= e($asset['name']); ?></p>
                        <p style="margin-top:.7rem;"><span class="badge <?= e(status_badge_class($asset['condition'])); ?>"><?= e($asset['condition']); ?></span></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p style="margin-top:2.2rem;"><a class="btn btn-outline" href="<?= base_url('/gedung'); ?>">&larr; Kembali ke Daftar Gedung</a></p>
    </div>
</section>