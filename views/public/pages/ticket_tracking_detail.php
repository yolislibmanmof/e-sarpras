<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$prioClass = ['Normal' => 'badge-info', 'Mendesak' => 'badge-warning', 'Darurat' => 'badge-danger'][$ticket['priority'] ?? 'Normal'] ?? 'badge-info';
$done = $ticket['status'] === 'Selesai';
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/lacak-laporan'); ?>">Lacak Laporan</a> &rarr; <?= e($ticket['ticket_code']); ?></span>
        <h1>Tiket <?= e($ticket['ticket_code']); ?></h1>
        <p><?= e($ticket['title'] ?? 'Laporan Kerusakan'); ?></p>
        <p style="margin-top:.8rem;">
            <span class="badge <?= e($prioClass); ?>">Prioritas <?= e($ticket['priority'] ?? 'Normal'); ?></span>
            <span class="badge <?= e(status_badge_class($ticket['status'])); ?>"><?= e($ticket['status']); ?></span>
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="card shine" style="max-width:820px;margin:0 auto;">

            <!-- HEADER INFO -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:1rem;margin-bottom:1.5rem;">
                <div><small style="color:var(--muted);">Lokasi</small><br><strong><?= e(trim(($ticket['building_name'] ?? '-') . ' / ' . ($ticket['room_name'] ?? '-'))); ?></strong></div>
                <div><small style="color:var(--muted);">Kategori</small><br><strong><?= e($ticket['category'] ?? '-'); ?></strong></div>
                <div><small style="color:var(--muted);">Estimasi Selesai</small><br><strong><?= e(format_tanggal_waktu($deadline)); ?></strong></div>
                <div><small style="color:var(--muted);">Sisa Waktu</small><br><strong id="countdown" data-deadline="<?= e($deadline); ?>" data-done="<?= $done ? '1' : '0'; ?>">-</strong></div>
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
                        <div class="<?= ($isLast && !$done) ? 'pulse' : ''; ?>" style="position:absolute;left:-32px;top:0;width:18px;height:18px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent-2));box-shadow:0 0 0 4px rgba(45,212,191,.2);"></div>
                        <div>
                            <strong style="color:var(--primary-dark);"><?= e($step['status']); ?></strong>
                            <div style="color:var(--muted);font-size:.82rem;"><?= e(format_tanggal_waktu($step['time'])); ?></div>
                            <?php if (!empty($step['note'])): ?>
                                <p style="margin:.3rem 0 0;font-size:.9rem;"><?= e($step['note']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$done): ?>
                    <div style="position:relative;padding-left:0;">
                        <div style="position:absolute;left:-32px;top:0;width:18px;height:18px;border-radius:50%;border:2px dashed var(--muted);background:var(--card);"></div>
                        <div>
                            <strong style="color:var(--muted);">Selesai</strong>
                            <div style="color:var(--muted);font-size:.82rem;font-style:italic;">Menunggu penyelesaian</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top:1.8rem;display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap;">
                <a class="btn btn-secondary btn-sm" href="<?= base_url('/lacak-laporan'); ?>?code=<?= e(urlencode($ticket['ticket_code'])); ?>">Ringkasan Tiket</a>
                <button class="btn btn-secondary btn-sm" type="button" onclick="window.print()">Cetak Halaman</button>
                <?php if ($done): ?>
                    <a class="btn btn-primary btn-sm" href="<?= base_url('/survei'); ?>">Beri Penilaian Layanan</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulseDot2 {0%,100%{box-shadow:0 0 0 4px rgba(45,212,191,.25)}50%{box-shadow:0 0 0 10px rgba(45,212,191,0)}}
.pulse{animation:pulseDot2 1.8s ease infinite}
@media print { .navbar, .site-footer, .no-print { display:none !important; } }
</style>

<script>
(function () {
    var el = document.getElementById('countdown');
    if (!el) { return; }
    var done = el.getAttribute('data-done') === '1';
    var dl = new Date(el.getAttribute('data-deadline').replace(' ', 'T'));
    function tick() {
        if (done) { el.textContent = 'Selesai'; return; }
        var diff = dl - new Date();
        if (diff <= 0) { el.textContent = 'Lewat batas'; el.style.color = 'var(--danger)'; return; }
        var d = Math.floor(diff / 86400000);
        var h = Math.floor(diff % 86400000 / 3600000);
        var m = Math.floor(diff % 3600000 / 60000);
        var s = Math.floor(diff % 60000 / 1000);
        el.textContent = (d > 0 ? d + ' hari ' : '') + h + ' jam ' + m + ' mnt ' + s + ' dtk';
    }
    tick();
    setInterval(tick, 1000);
})();
</script>