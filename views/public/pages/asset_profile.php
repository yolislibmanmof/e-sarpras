<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a> &rarr; Profil Aset</span>
        <h1><?= e($asset['name']); ?></h1>
        <p><?= e($asset['code']); ?> &middot; <?= e($asset['category_name'] ?? '-'); ?></p>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <div class="cards-grid-2">
            <div class="card shine">
                <h3 class="card-title">Informasi Aset</h3>
                <ul class="mini-steps" style="margin-top:1rem;">
                    <li><b>&#9632;</b> Kode: <strong><?= e($asset['code']); ?></strong></li>
                    <li><b>&#9632;</b> Kategori: <strong><?= e($asset['category_name'] ?? '-'); ?></strong></li>
                    <li><b>&#9632;</b> Merek/Model: <strong><?= e(trim(($asset['brand'] ?? '-') . ' / ' . ($asset['model'] ?? '-'))); ?></strong></li>
                    <li><b>&#9632;</b> Tahun: <strong><?= e($asset['year'] ?? '-'); ?></strong></li>
                    <li><b>&#9632;</b> Lokasi: <strong><?= e(trim(($asset['building_name'] ?? '-') . ' / ' . ($asset['room_name'] ?? '-'))); ?></strong></li>
                    <li><b>&#9632;</b> Kondisi: <span class="badge <?= e(status_badge_class($asset['condition'])); ?>"><?= e($asset['condition']); ?></span></li>
                    <li><b>&#9632;</b> Status: <span class="badge badge-info"><?= e($asset['status']); ?></span></li>
                </ul>
                <?php if (!empty($asset['specification'])): ?>
                    <h4 class="sub-title">Spesifikasi</h4>
                    <p><?= e($asset['specification']); ?></p>
                <?php endif; ?>
            </div>

            <div class="card shine" style="text-align:center;">
                <h3 class="card-title">QR / Barcode Aset</h3>
                <img src="<?= e($qrUrl); ?>" alt="QR Aset" style="width:200px;height:auto;margin:0 auto;border-radius:12px;border:1px solid var(--border);background:#fff;padding:.6rem;">
                <p style="margin-top:.8rem;font-size:.8rem;color:var(--muted);">Pindai untuk membuka profil aset ini.</p>
                <p style="font-size:.76rem;"><code><?= e($publicUrl); ?></code></p>
            </div>
        </div>

        <div class="cards-grid-2" style="margin-top:1.4rem;">
            <div class="card shine">
                <h3 class="card-title">Riwayat Kondisi</h3>
                <?php if ($conditions === []): ?>
                    <p class="empty-note">Belum ada riwayat kondisi.</p>
                <?php else: ?>
                    <ul class="list-plain">
                        <?php foreach ($conditions as $c): ?>
                            <li>
                                <span><span class="badge <?= e(status_badge_class($c['condition'])); ?>"><?= e($c['condition']); ?></span> <?= e($c['note'] ?? ''); ?></span>
                                <span style="color:var(--muted);font-size:.78rem;"><?= e(format_tanggal($c['created_at'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="card shine">
                <h3 class="card-title">Pemeliharaan Terakhir</h3>
                <?php if ($maintenances === []): ?>
                    <p class="empty-note">Belum ada jadwal pemeliharaan.</p>
                <?php else: ?>
                    <ul class="list-plain">
                        <?php foreach ($maintenances as $m): ?>
                            <li>
                                <span><?= e($m['title']); ?></span>
                                <span><span class="badge <?= e(status_badge_class($m['status'])); ?>"><?= e($m['status']); ?></span> <?= e(format_tanggal($m['schedule_date'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <p style="margin-top:2.2rem;"><a class="btn btn-outline" href="<?= base_url('/gedung'); ?>">&larr; Kembali</a></p>
    </div>
</section>