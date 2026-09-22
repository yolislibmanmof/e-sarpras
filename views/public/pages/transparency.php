<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Transparansi</span>
        <h1>Dashboard Transparansi Sarpras</h1>
        <p>Data terbuka tentang pengelolaan sarana dan prasarana kampus &mdash; akuntabel untuk sivitas akademika.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card shine">
                <span class="stat-value"><?= number_format($stats['total_assets']); ?></span>
                <span class="stat-label">Total Aset Tercatat</span>
            </div>
            <div class="stat-card shine">
                <span class="stat-value"><?= $stats['total_assets'] > 0 ? number_format($stats['assets_good'] / $stats['total_assets'] * 100, 1) : 0; ?>%</span>
                <span class="stat-label">Aset Kondisi Baik</span>
            </div>
            <div class="stat-card shine">
                <span class="stat-value"><?= number_format($stats['total_rooms']); ?></span>
                <span class="stat-label">Ruangan Terdaftar</span>
            </div>
            <div class="stat-card shine">
                <span class="stat-value"><?= number_format($stats['tickets_resolved']); ?></span>
                <span class="stat-label">Tiket Selesai</span>
            </div>
            <div class="stat-card shine">
                <span class="stat-value"><?= number_format($stats['tickets_this_month']); ?></span>
                <span class="stat-label">Laporan Bulan Ini</span>
            </div>
            <div class="stat-card shine">
                <span class="stat-value"><?= number_format($stats['surveys_answered']); ?></span>
                <span class="stat-label">Respons Survei</span>
            </div>
        </div>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <div class="cards-grid-2">
            <div class="card shine">
                <h3>Ruangan Paling Banyak Dipinjam</h3>
                <?php if ($topRooms === []): ?>
                    <p class="empty-note">Belum ada data peminjaman.</p>
                <?php else: ?>
                    <?php $max = max(1, (int) max(array_column($topRooms, 'total'))); ?>
                    <?php foreach ($topRooms as $r): ?>
                        <div style="margin:.6rem 0;">
                            <div style="display:flex;justify-content:space-between;font-size:.88rem;margin-bottom:.3rem;">
                                <span><strong><?= e($r['name']); ?></strong></span>
                                <span><?= (int) $r['total']; ?> kali</span>
                            </div>
                            <div style="background:var(--bg);border-radius:999px;height:8px;overflow:hidden;">
                                <div style="width:<?= (int) round($r['total'] / $max * 100); ?>%;height:100%;background:linear-gradient(90deg,var(--primary),var(--accent-2));border-radius:999px;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="card shine">
                <h3>Janji Layanan Kami</h3>
                <ul class="check-list">
                    <li>Respons laporan kerusakan maksimal <strong>2&times;24 jam</strong>.</li>
                    <li>Pemeliharaan APAR terjadwal <strong>setiap 6 bulan</strong>.</li>
                    <li>Transparansi data inventaris terbuka untuk sivitas.</li>
                    <li>Survei kepuasan dilakukan <strong>setiap semester</strong>.</li>
                    <li>Audit aset dilakukan <strong>setiap tahun</strong>.</li>
                </ul>
                <p style="margin-top:1rem;"><a class="btn btn-secondary btn-sm" href="<?= base_url('/survei'); ?>">Ikuti Survei Kepuasan</a></p>
            </div>
        </div>
    </div>
</section>