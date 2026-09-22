<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$condColors = ['Baik' => '#16a34a', 'Rusak Ringan' => '#d97706', 'Rusak Sedang' => '#ea580c', 'Rusak Berat' => '#dc2626'];
$condTotal = array_sum($condTotals);
$maxMonth = max(1, max($months));
$satPct = $avgRating > 0 ? min(100, round($avgRating / 5 * 100)) : 0;
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Transparansi</span>
        <h1>Dashboard Transparansi Sarpras</h1>
        <p>Data terbuka tentang pengelolaan sarana dan prasarana kampus &mdash; wujud akuntabilitas kami kepada seluruh sivitas akademika.</p>
        <p style="margin-top:.8rem;"><span class="badge badge-success"><span class="live-dot" style="margin-right:.4rem;"></span>Data diperbarui <?= e($lastUpdated); ?></span></p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card shine"><span><span class="stat-value"><?= number_format((int) $stats['total_assets']); ?></span><span class="stat-label">Total Aset Tercatat</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= $stats['total_assets'] > 0 ? number_format($stats['assets_good'] / $stats['total_assets'] * 100, 1) : '0'; ?>%</span><span class="stat-label">Aset Kondisi Baik</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= number_format((int) $stats['total_rooms']); ?></span><span class="stat-label">Ruangan Terdaftar</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= number_format((int) $stats['tickets_resolved']); ?></span><span class="stat-label">Tiket Selesai</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= number_format((int) $stats['tickets_this_month']); ?></span><span class="stat-label">Laporan Bulan Ini</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= number_format((int) $stats['surveys_answered']); ?></span><span class="stat-label">Respons Survei</span></span></div>
        </div>
    </div>
</section>

<!-- ============ GRAFIK UTAMA ============ -->
<section class="section" style="padding-top:1rem;">
    <div class="container">
        <div class="cards-grid" style="grid-template-columns:1.2fr 1fr 0.8fr;">

            <!-- Tren laporan 6 bulan -->
            <div class="card shine">
                <h3 class="card-title">Tren Laporan 6 Bulan</h3>
                <svg viewBox="0 0 300 120" style="width:100%;height:auto;">
                    <defs>
                        <linearGradient id="trendFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#2dd4bf" stop-opacity=".45"/>
                            <stop offset="1" stop-color="#2dd4bf" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <?php
                    $pts = [];
                    $i = 0;
                    foreach ($months as $ym => $v) {
                        $x = 20 + $i * 52;
                        $y = 100 - ($v / $maxMonth) * 80;
                        $pts[] = [$x, $y, $v, $ym];
                        $i++;
                    }
                    $line = implode(' ', array_map(static fn ($p) => $p[0] . ',' . $p[1], $pts));
                    $area = '20,100 ' . $line . ' ' . (20 + (count($pts) - 1) * 52) . ',100';
                    ?>
                    <polygon points="<?= e($area); ?>" fill="url(#trendFill)"/>
                    <polyline points="<?= e($line); ?>" fill="none" stroke="#0f6f5c" stroke-width="2.5" stroke-linecap="round"/>
                    <?php foreach ($pts as $p): ?>
                        <circle cx="<?= (int) $p[0]; ?>" cy="<?= (int) $p[1]; ?>" r="4" fill="#2dd4bf" stroke="#fff" stroke-width="1.5"><title><?= e(date('M Y', strtotime($p[3] . '-01'))); ?>: <?= (int) $p[2]; ?> laporan</title></circle>
                        <text x="<?= (int) $p[0]; ?>" y="114" text-anchor="middle" font-size="9" fill="#64748b"><?= e(date('M', strtotime($p[3] . '-01'))); ?></text>
                    <?php endforeach; ?>
                </svg>
            </div>

            <!-- Komposisi kondisi aset -->
            <div class="card shine">
                <h3 class="card-title">Komposisi Kondisi Aset</h3>
                <?php if ($condTotal === 0): ?>
                    <p class="empty-note">Belum ada aset tercatat.</p>
                <?php else: ?>
                    <div style="display:flex;height:16px;border-radius:999px;overflow:hidden;background:var(--bg);">
                        <?php foreach ($condTotals as $label => $val): ?>
                            <?php if ($val > 0): ?>
                                <div style="width:<?= round($val / $condTotal * 100, 2); ?>%;background:<?= $condColors[$label]; ?>;" title="<?= e($label); ?>: <?= (int) $val; ?>"></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:.7rem;margin-top:.9rem;">
                        <?php foreach ($condTotals as $label => $val): ?>
                            <span style="display:inline-flex;align-items:center;gap:.35rem;font-size:.76rem;color:var(--muted);">
                                <i style="width:10px;height:10px;border-radius:3px;background:<?= $condColors[$label]; ?>;display:inline-block;"></i>
                                <?= e($label); ?> (<?= (int) $val; ?>)
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h4 class="sub-title">Status Tiket Saat Ini</h4>
                <?php if ($ticketsByStatus === []): ?>
                    <p class="empty-note">Belum ada tiket.</p>
                <?php else: ?>
                    <?php $maxSt = max(1, (int) max(array_column($ticketsByStatus, 'total'))); ?>
                    <?php foreach ($ticketsByStatus as $st): ?>
                        <div style="margin:.45rem 0;">
                            <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.25rem;">
                                <span><?= e($st['status']); ?></span><strong><?= (int) $st['total']; ?></strong>
                            </div>
                            <div style="background:var(--bg);border-radius:999px;height:7px;overflow:hidden;">
                                <div style="width:<?= (int) round($st['total'] / $maxSt * 100); ?>%;height:100%;background:linear-gradient(90deg,var(--primary),var(--accent-2));border-radius:999px;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Cincin kepuasan -->
            <div class="card shine" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
                <h3 class="card-title">Kepuasan Sivitas</h3>
                <div class="ring" style="--off:<?= (int) (264 * (1 - $satPct / 100)); ?>">
                    <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42"/></svg>
                    <div class="val"><?= $avgRating > 0 ? number_format($avgRating, 1) : '-'; ?><small>dari 5.0</small></div>
                </div>
                <p style="margin-top:.8rem;font-size:.82rem;"><?= number_format($ratingCount); ?> jawaban rating terkumpul</p>
                <a class="btn btn-secondary btn-sm" style="margin-top:.6rem;" href="<?= base_url('/survei'); ?>">Beri Penilaian</a>
            </div>
        </div>
    </div>
</section>

<!-- ============ DETAIL & AKTIVITAS ============ -->
<section class="section page-body" style="padding-top:1rem;">
    <div class="container">
        <div class="cards-grid-2">
            <div class="card shine">
                <h3 class="card-title">Ruangan Paling Banyak Dipinjam</h3>
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

                <h4 class="sub-title">Aktivitas Penanganan Terbaru</h4>
                <?php if ($feed === []): ?>
                    <p class="empty-note">Belum ada aktivitas.</p>
                <?php else: ?>
                    <ul class="list-plain" style="margin-top:.4rem;">
                        <?php foreach ($feed as $f): ?>
                            <li>
                                <span><span class="badge <?= e(status_badge_class($f['new_status'])); ?>"><?= e($f['new_status']); ?></span> &nbsp;<?= e($f['ticket_code']); ?></span>
                                <span style="color:var(--muted);font-size:.78rem;"><?= e(format_tanggal_waktu($f['created_at'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="card shine">
                <h3 class="card-title">Janji Layanan Kami</h3>
                <ul class="check-list">
                    <li>Respons laporan kerusakan maksimal <strong>2&times;24 jam</strong> kerja.</li>
                    <li>Pemeriksaan APAR terjadwal <strong>setiap 6 bulan</strong>.</li>
                    <li>Data inventaris terbuka untuk seluruh sivitas akademika.</li>
                    <li>Survei kepuasan dilaksanakan <strong>setiap semester</strong>.</li>
                    <li>Audit aset dilakukan <strong>sekali setiap tahun</strong>.</li>
                </ul>
                <div class="form-actions" style="margin-top:1.2rem;">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('/lapor-kerusakan'); ?>">Lapor Kerusakan</a>
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('/peminjaman'); ?>">Peminjaman</a>
                    <a class="btn btn-outline btn-sm" href="<?= base_url('/survei'); ?>">Ikuti Survei</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width:1000px){ .cards-grid[style*="1.2fr"]{ grid-template-columns:1fr !important; } }
</style>