<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a> &rarr; Jadwal</span>
        <h1>Jadwal <?= e($room['name']); ?></h1>
        <p><?= e($room['building_name']); ?> &middot; Kapasitas <?= (int) $room['capacity']; ?> orang</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card shine" style="max-width:900px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;flex-wrap:wrap;gap:.6rem;">
                <h3>Ketersediaan 14 Hari ke Depan</h3>
                <a class="btn btn-primary btn-sm" href="<?= base_url('/peminjaman/ruangan'); ?>">Ajukan Peminjaman</a>
            </div>

            <?php foreach ($byDate as $date => $slots): ?>
                <div style="margin-bottom:1.2rem;border-bottom:1px solid var(--border);padding-bottom:1rem;">
                    <div style="font-weight:700;color:var(--primary-dark);margin-bottom:.6rem;">
                        <?= e(format_tanggal_panjang($date)); ?>
                    </div>
                    <?php if ($slots === []): ?>
                        <div style="padding:.5rem .9rem;background:#ecfdf5;border-radius:10px;color:#065f46;font-size:.88rem;">
                            ✓ Seluruh hari tersedia
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
    </div>
</section>