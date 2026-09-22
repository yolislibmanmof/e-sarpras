<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$bookedDays = 0; $freeDays = 0; $nextSlot = null;
foreach ($byDate as $date => $slots) {
    if ($slots === []) { $freeDays++; } else { $bookedDays++; if ($nextSlot === null) { $nextSlot = ['date' => $date, 'slot' => $slots[0]]; } }
}
$utilPct = (int) round($bookedDays / max(1, count($byDate)) * 100);
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a> &rarr; Jadwal</span>
        <h1>Jadwal <?= e($room['name']); ?></h1>
        <p><?= e($room['building_name']); ?> &middot; Kapasitas <?= (int) $room['capacity']; ?> orang</p>
        <p style="margin-top:.8rem;">
            <span class="badge badge-success"><?= (int) $freeDays; ?> hari tersedia</span>
            <span class="badge badge-warning"><?= (int) $bookedDays; ?> hari terpesan</span>
            <span class="badge badge-info">Utilisasi <?= (int) $utilPct; ?>%</span>
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card shine" style="max-width:900px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
                <h3 style="margin:0;">Ketersediaan 14 Hari ke Depan</h3>
                <a class="btn btn-primary btn-sm" href="<?= base_url('/peminjaman/ruangan'); ?>">Ajukan Peminjaman</a>
            </div>

            <?php if ($nextSlot !== null): ?>
            <div class="public-alert public-alert-success" style="margin-bottom:1.2rem;">
                Pemesanan terdekat: <strong><?= e(format_tanggal_panjang($nextSlot['date'])); ?></strong> pukul <?= e($nextSlot['slot']['start']); ?> &ndash; <?= e($nextSlot['slot']['end']); ?> (<?= e($nextSlot['slot']['title']); ?>)
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:1.2rem;margin-bottom:1.2rem;font-size:.8rem;color:var(--muted);">
                <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#16a34a;margin-right:.35rem;"></i>Tersedia</span>
                <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#d97706;margin-right:.35rem;"></i>Terpesan</span>
            </div>

            <?php foreach ($byDate as $date => $slots): ?>
                <div style="margin-bottom:1.2rem;border-bottom:1px solid var(--border);padding-bottom:1rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:.6rem;flex-wrap:wrap;margin-bottom:.6rem;">
                        <div style="font-weight:700;color:var(--primary-dark);"><?= e(format_tanggal_panjang($date)); ?></div>
                        <?php if ($slots === []): ?>
                            <a class="btn btn-secondary btn-sm" href="<?= base_url('/peminjaman/ruangan'); ?>">Pesan Hari Ini</a>
                        <?php endif; ?>
                    </div>
                    <?php if ($slots === []): ?>
                        <div style="padding:.5rem .9rem;background:#ecfdf5;border-radius:10px;color:#065f46;font-size:.88rem;">
                            &#10003; Seluruh hari tersedia
                        </div>
                    <?php else: ?>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            <?php foreach ($slots as $s): ?>
                                <span class="badge badge-warning" style="font-size:.82rem;padding:.35rem .75rem;">
                                    <?= e($s['start']); ?> &ndash; <?= e($s['end']); ?> &middot; <?= e($s['title']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <p style="margin-top:1.6rem;text-align:center;">
            <a class="btn btn-outline" href="<?= base_url('/ruangan/' . (int) $room['id']); ?>">&larr; Kembali ke Detail Ruangan</a>
        </p>
    </div>
</section>