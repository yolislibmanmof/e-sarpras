<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/lacak-laporan'); ?>">Lacak Laporan</a> &rarr; #<?= e($ticket['code']); ?></span>
        <h1>Tiket #<?= e($ticket['code']); ?></h1>
        <p><?= e($ticket['subject'] ?? 'Laporan Kerusakan'); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card shine" style="max-width:820px;margin:0 auto;">

            <!-- HEADER INFO -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
                <div><small style="color:var(--muted);">Urgensi</small><br><span class="badge <?= ['Rendah'=>'badge-info','Sedang'=>'badge-warning','Tinggi'=>'badge-danger','Darurat'=>'badge-danger'][e($ticket['urgency'] ?? 'Sedang')] ?? 'badge-info'; ?>"><?= e($ticket['urgency'] ?? 'Sedang'); ?></span></div>
                <div><small style="color:var(--muted);">Status</small><br><strong><?= e($ticket['status']); ?></strong></div>
                <div><small style="color:var(--muted);">Estimasi Selesai</small><br><strong><?= e(format_tanggal_waktu($deadline)); ?></strong></div>
                <div><small style="color:var(--muted);">Sisa Waktu</small><br><strong><?= $ticket['status'] === 'Selesai' ? 'Selesai' : (int) $remaining . ' jam'; ?></strong></div>
            </div>

            <!-- PROGRESS BAR -->
            <div style="margin-bottom:1.8rem;">
                <div style="display:flex;justify-content:space-between;margin-bottom:.4rem;">
                    <small style="color:var(--muted);">Progress</small>
                    <strong><?= (int) $progressPct; ?>%</strong>
                </div>
                <div style="background:var(--bg);border-radius:999px;height:10px;overflow:hidden;">
                    <div style="width:<?= (int) $progressPct; ?>%;height:100%;background:linear-gradient(90deg,var(--primary),var(--accent-2));border-radius:999px;transition:width .6s;"></div>
                </div>
            </div>

            <!-- TIMELINE ala KURIR -->
            <h3 style="margin-bottom:1rem;">Perjalanan Tiket</h3>
            <div style="position:relative;padding-left:32px;">
                <?php foreach ($timeline as $i => $step): ?>
                    <?php $isLast = $i === count($timeline) - 1; ?>
                    <div style="position:relative;padding-bottom:<?= $isLast ? '0' : '1.6rem'; ?>;">
                        <?php if (!$isLast): ?>
                            <div style="position:absolute;left:-24px;top:12px;bottom:-12px;width:2px;background:linear-gradient(var(--primary),var(--accent-2));"></div>
                        <?php endif; ?>
                        <div style="position:absolute;left:-32px;top:0;width:18px;height:18px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent-2));box-shadow:0 0 0 4px rgba(45,212,191,.2);"></div>
                        <div>
                            <strong style="color:var(--primary-dark);"><?= e($step['status']); ?></strong>
                            <div style="color:var(--muted);font-size:.82rem;"><?= e(format_tanggal_waktu($step['time'])); ?></div>
                            <p style="margin:.3rem 0 0;font-size:.9rem;"><?= e($step['note']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if ($ticket['status'] !== 'Selesai'): ?>
                    <div style="position:relative;padding-left:0;">
                        <div style="position:absolute;left:-32px;top:0;width:18px;height:18px;border-radius:50%;border:2px dashed var(--muted);background:var(--card);"></div>
                        <div>
                            <strong style="color:var(--muted);">Selesai</strong>
                            <div style="color:var(--muted);font-size:.82rem;font-style:italic;">Menunggu penyelesaian</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>