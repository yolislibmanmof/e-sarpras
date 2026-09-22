<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$goodRooms = 0;
foreach ($rooms as $room) { if ($room['condition'] === 'Baik') { $goodRooms++; } }
$pctGood = $stats['rooms'] > 0 ? $goodRooms / $stats['rooms'] : 0;
$pctAcc = $stats['rooms'] > 0 ? $stats['accessible'] / $stats['rooms'] : 0;

$perFloor = [];
foreach ($rooms as $r) {
    $key = $r['floor_id'] !== null ? (int) $r['floor_id'] : 0;
    $perFloor[$key] = ($perFloor[$key] ?? 0) + 1;
}
$maxFloor = max(1, max($perFloor !== [] ? $perFloor : [1]));
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a> &rarr; <?= e($building['code']); ?></span>
        <h1><?= e($building['name']); ?></h1>
        <p>Kode <?= e($building['code']); ?> &middot; <?= e($building['ownership_status'] ?? '-'); ?></p>
        <p style="margin-top:.8rem;">
            <span class="badge <?= e(status_badge_class($building['condition'])); ?>"><?= e($building['condition']); ?></span>
            <?php if ((int) $building['is_disability_friendly'] === 1): ?><span class="badge badge-success">Ramah Disabilitas</span><?php endif; ?>
            <span class="badge badge-info"><?= (int) $stats['rooms']; ?> ruangan</span>
            <span class="badge badge-warning"><?= (int) $stats['floors']; ?> lantai</span>
        </p>
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
                            <span class="badge badge-info">L<?= (int) $floor['level']; ?> &middot; <?= e($floor['name']); ?> (<?= (int) ($perFloor[(int) $floor['id']] ?? 0); ?> ruang)</span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shine" style="margin-top:1.4rem;display:flex;gap:1rem;align-items:center;justify-content:space-between;flex-wrap:wrap;">
            <div style="display:flex;gap:1rem;align-items:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:36px;height:36px;color:var(--primary);flex-shrink:0;"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                <div>
                    <h3 style="margin:0;">Denah Interaktif</h3>
                    <p style="margin:.2rem 0 0;">Jelajahi gedung ini pada peta kampus dan klik ruangan untuk melihat detailnya.</p>
                </div>
            </div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('/peta?b=' . (int) $building['id']); ?>">Buka Peta</a>
        </div>

        <?php if ($floors !== []): ?>
        <h2 class="section-title" style="margin-top:2.6rem;">Sebaran Ruangan per Lantai</h2>
        <div class="card shine" style="padding:1.2rem;">
            <?php foreach ($floors as $floor): ?>
                <?php $cnt = (int) ($perFloor[(int) $floor['id']] ?? 0); ?>
                <div style="margin:.6rem 0;">
                    <div style="display:flex;justify-content:space-between;font-size:.86rem;margin-bottom:.3rem;">
                        <span><strong>L<?= (int) $floor['level']; ?> &middot; <?= e($floor['name']); ?></strong></span>
                        <span><?= $cnt; ?> ruangan</span>
                    </div>
                    <div style="background:var(--bg);border-radius:999px;height:8px;overflow:hidden;">
                        <div style="width:<?= (int) round($cnt / $maxFloor * 100); ?>%;height:100%;background:linear-gradient(90deg,var(--primary),var(--accent-2));border-radius:999px;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

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
                        <div style="display:flex;gap:1rem;margin-top:.6rem;flex-wrap:wrap;">
                            <a class="service-link" href="<?= base_url('/ruangan/' . (int) $room['id']); ?>">Lihat Detail</a>
                            <a class="service-link" href="<?= base_url('/ruangan/' . (int) $room['id'] . '/jadwal'); ?>">Jadwal</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p style="margin-top:2.2rem;"><a class="btn btn-outline" href="<?= base_url('/gedung'); ?>">&larr; Kembali ke Daftar Gedung</a></p>
    </div>
</section>