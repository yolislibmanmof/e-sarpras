<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$dbA = \App\Core\Database::instance();
$maintCount = (int) ($dbA->selectOne('SELECT COUNT(*) AS c FROM maintenance_schedules WHERE asset_id = ?', [$asset['id']])['c'] ?? 0);
$lastMaint  = $dbA->selectOne('SELECT title, schedule_date, status FROM maintenance_schedules WHERE asset_id = ? ORDER BY schedule_date DESC LIMIT 1', [$asset['id']]);
$nextMaint  = $dbA->selectOne('SELECT title, schedule_date FROM maintenance_schedules WHERE asset_id = ? AND schedule_date >= CURDATE() ORDER BY schedule_date ASC LIMIT 1', [$asset['id']]);
$ageYears   = !empty($asset['year']) ? max(0, (int) date('Y') - (int) $asset['year']) : null;
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a> &rarr; Profil Aset</span>
        <h1><?= e($asset['name']); ?></h1>
        <p><?= e($asset['code']); ?> &middot; <?= e($asset['category_name'] ?? '-'); ?></p>
        <p style="margin-top:.8rem;">
            <span class="badge <?= e(status_badge_class($asset['condition'])); ?>"><?= e($asset['condition']); ?></span>
            <span class="badge badge-info"><?= e($asset['status']); ?></span>
            <?php if ($ageYears !== null): ?><span class="badge badge-warning">Usia <?= (int) $ageYears; ?> tahun</span><?php endif; ?>
        </p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card shine"><span><span class="stat-value"><?= $ageYears !== null ? (int) $ageYears : '-'; ?></span><span class="stat-label">Usia Aset (tahun)</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $maintCount; ?></span><span class="stat-label">Total Pemeliharaan</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= $nextMaint !== null ? e(format_tanggal($nextMaint['schedule_date'])) : '-'; ?></span><span class="stat-label">Pemeliharaan Berikutnya</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= $lastMaint !== null ? e(format_tanggal($lastMaint['schedule_date'])) : '-'; ?></span><span class="stat-label">Pemeliharaan Terakhir</span></span></div>
        </div>
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
                <div style="display:inline-block;padding:.8rem;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--accent-2));box-shadow:var(--shadow-lg);">
                    <img src="<?= e($qrUrl); ?>" alt="QR Aset" style="width:190px;height:auto;display:block;border-radius:10px;background:#fff;padding:.5rem;">
                </div>
                <p style="margin-top:.9rem;font-size:.8rem;color:var(--muted);">Pindai untuk membuka profil aset ini.</p>
                <p style="font-size:.76rem;"><code><?= e($publicUrl); ?></code></p>
                <div style="display:flex;gap:.6rem;justify-content:center;margin-top:1rem;">
                    <a class="btn btn-secondary btn-sm" href="<?= e($qrUrl); ?>" download="qr-<?= e($asset['code']); ?>.svg">Unduh QR</a>
                    <button class="btn btn-outline btn-sm" type="button" data-url="<?= e($publicUrl); ?>" onclick="copyAssetUrl(this)">Salin Tautan</button>
                </div>
            </div>
        </div>

        <div class="cards-grid-2" style="margin-top:1.4rem;">
            <div class="card shine">
                <h3 class="card-title">Lokasi Aset</h3>
                <p style="margin-top:.6rem;"><?= e(trim(($asset['building_name'] ?? '-') . ' / ' . ($asset['room_name'] ?? '-'))); ?></p>
                <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1rem;">
                    <?php if (!empty($asset['room_id'])): ?>
                        <a class="btn btn-secondary btn-sm" href="<?= base_url('/ruangan/' . (int) $asset['room_id']); ?>">Lihat Ruangan</a>
                    <?php endif; ?>
                    <?php if (!empty($asset['building_id'])): ?>
                        <a class="btn btn-secondary btn-sm" href="<?= base_url('/gedung/' . (int) $asset['building_id']); ?>">Lihat Gedung</a>
                        <a class="btn btn-outline btn-sm" href="<?= base_url('/peta?b=' . (int) $asset['building_id']); ?>">Buka Peta</a>
                    <?php endif; ?>
                </div>
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

        <h2 class="section-title" style="margin-top:2.6rem;">Riwayat Kondisi</h2>
        <?php if ($conditions === []): ?>
            <div class="public-alert public-alert-error">Belum ada riwayat kondisi.</div>
        <?php else: ?>
            <div class="vtimeline" style="max-width:660px;margin:1.6rem auto 0;">
                <span class="vt-line"><i style="height:100%;"></i></span>
                <?php $n = 1; foreach ($conditions as $c): ?>
                    <div class="vt-item active">
                        <span class="vt-dot"><?= $n++; ?></span>
                        <div class="card vt-card">
                            <h3><span class="badge <?= e(status_badge_class($c['condition'])); ?>"><?= e($c['condition']); ?></span></h3>
                            <p><?= e($c['note'] ?? ''); ?> &middot; <?= e(format_tanggal($c['created_at'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p style="margin-top:2.2rem;"><a class="btn btn-outline" href="<?= base_url('/gedung'); ?>">&larr; Kembali</a></p>
    </div>
</section>

<script>
function copyAssetUrl(btn) {
    var url = btn.getAttribute('data-url');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function () {
            btn.textContent = 'Tersalin!';
            setTimeout(function () { btn.textContent = 'Salin Tautan'; }, 1500);
        });
    }
}
</script>