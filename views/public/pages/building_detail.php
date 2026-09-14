<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$goodRooms = 0;
foreach ($rooms as $room) { if ($room['condition'] === 'Baik') { $goodRooms++; } }
$pctGood = $stats['rooms'] > 0 ? $goodRooms / $stats['rooms'] : 0;
$pctAcc = $stats['rooms'] > 0 ? $stats['accessible'] / $stats['rooms'] : 0;
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a> &rarr; <?= e($building['code']); ?></span>
        <h1><?= e($building['name']); ?></h1>
        <p>Kode <?= e($building['code']); ?> &middot; <?= e($building['ownership_status'] ?? '-'); ?></p>
    </div>
</section>

<?php if (!empty($building['photo'])): ?>
<section style="padding:1.6rem 0 0;">
    <div class="container">
        <img src="<?= base_url('/media/' . $building['photo']); ?>" alt="<?= e($building['name']); ?>" style="width:100%;max-height:340px;object-fit:cover;border-radius:20px;box-shadow:var(--shadow-lg);">
    </div>
</section>
<?php endif; ?>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="ring-wrap">
            <div class="ring" style="--off:<?= (int) (264 * (1 - $pctGood)); ?>">
                <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42"/></svg>
                <div class="val"><?= (int) round($pctGood * 100); ?>%<small>Ruang Kondisi Baik</small></div>
            </div>
            <div class="ring" style="--off:<?= (int) (264 * (1 - $pctAcc)); ?>">
                <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42"/></svg>
                <div class="val"><?= (int) round($pctAcc * 100); ?>%<small>Ruang Aksesibel</small></div>
            </div>
            <div class="ring" style="--off:0">
                <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42"/></svg>
                <div class="val"><?= (int) $stats['assets']; ?><small>Aset Tercatat</small></div>
            </div>
        </div>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <div class="cards-grid-2">
            <div class="card shine">
                <h3>Informasi Gedung</h3>
                <p><?= e($building['address'] ?? 'Alamat belum dicantumkan.'); ?></p>
                <ul class="mini-steps" style="margin-top:1rem;">
                    <li><b>&#9632;</b> Tahun pembangunan: <strong><?= e($building['year_built'] ?? '-'); ?></strong></li>
                    <li><b>&#9632;</b> Luas lahan: <strong><?= e($building['land_area'] ?? '-'); ?> m2</strong></li>
                    <li><b>&#9632;</b> Luas bangunan: <strong><?= e($building['building_area'] ?? '-'); ?> m2</strong></li>
                    <li><b>&#9632;</b> Kondisi: <span class="badge <?= e(status_badge_class($building['condition'])); ?>"><?= e($building['condition']); ?></span></li>
                </ul>
            </div>
            <div class="card shine">
                <h3>Fasilitas Aksesibilitas</h3>
                <ul class="check-list">
                    <?php if ((int) $building['is_disability_friendly'] === 1): ?><li>Teridentifikasi ramah disabilitas.</li><?php endif; ?>
                    <?php if ((int) $building['has_ramp'] === 1): ?><li>Jalur landai (ramp) tersedia.</li><?php endif; ?>
                    <?php if ((int) $building['has_disability_toilet'] === 1): ?><li>Toilet khusus disabilitas.</li><?php endif; ?>
                    <?php if ((int) $building['has_lift'] === 1): ?><li>Lift antar lantai.</li><?php endif; ?>
                    <?php if ((int) $building['has_guide_path'] === 1): ?><li>Jalur pemandu (guiding block).</li><?php endif; ?>
                </ul>
                <?php if ($floors !== []): ?>
                    <h4 style="margin:1rem 0 0.6rem; color:var(--primary-dark);">Lantai</h4>
                    <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                        <?php foreach ($floors as $floor): ?>
                            <span class="badge badge-info">L<?= (int) $floor['level']; ?> &middot; <?= e($floor['name']); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="section-title" style="margin-top:2.6rem;">Daftar Ruangan</h2>
        <?php if ($rooms === []): ?>
            <div class="public-alert public-alert-error">Belum ada ruangan pada gedung ini.</div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($rooms as $room): ?>
                    <div class="card bento shine">
                        <?php if (!empty($room['photo'])): ?>
                            <img src="<?= base_url('/media/' . $room['photo']); ?>" alt="<?= e($room['name']); ?>" style="width:100%;height:130px;object-fit:cover;border-radius:12px;margin-bottom:.9rem;">
                        <?php endif; ?>
                        <div class="bento-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-4h6v4"/></svg>
                        </div>
                        <h3><?= e($room['code']); ?> - <?= e($room['name']); ?></h3>
                        <p><?= e($room['room_type']); ?> &middot; Kapasitas <?= (int) $room['capacity']; ?> orang</p>
                        <p style="margin-top:0.7rem;">
                            <span class="badge <?= e(status_badge_class($room['condition'])); ?>"><?= e($room['condition']); ?></span>
                            <?php if ((int) $room['is_disability_friendly'] === 1): ?>
                                <span class="badge badge-success">Aksesibel</span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p style="margin-top:2.2rem;"><a class="btn btn-outline" href="<?= base_url('/gedung'); ?>">&larr; Kembali ke Daftar Gedung</a></p>
    </div>
</section>