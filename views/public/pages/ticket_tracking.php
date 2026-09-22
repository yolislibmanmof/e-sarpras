<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$order = ['Menunggu Verifikasi' => 1, 'Diverifikasi' => 2, 'Menunggu Sparepart' => 3, 'Sedang Diperbaiki' => 3, 'Selesai' => 4, 'Ditolak' => 0];
$current = $ticket !== null ? (int) ($order[$ticket['status']] ?? 1) : 0;
$labels = ['Dilaporkan', 'Verifikasi', 'Perbaikan', 'Selesai'];
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Lacak Laporan</span>
        <h1>Lacak Laporan</h1>
        <p>Masukkan kode tiket Anda untuk melihat status penanganan laporan secara langsung.</p>
        <p style="margin-top:.8rem;"><span class="badge badge-success"><span class="live-dot" style="margin-right:.4rem;"></span>Pembaruan status waktu nyata</span></p>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <form method="GET" action="<?= base_url('/lacak-laporan'); ?>" class="tracking-form">
            <input type="text" name="code" value="<?= e($code); ?>" placeholder="Contoh: TKT-20260825-0001" required>
            <button type="submit" class="btn btn-primary">Lacak</button>
        </form>

        <?php if ($code === '' || $ticket === null): ?>
        <div class="cards-grid" style="grid-template-columns:repeat(3,1fr);margin-top:1.6rem;">
            <div class="card shine">
                <span style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">1</span>
                <h3>Salin Kode Tiket</h3>
                <p>Kode diberikan sesaat setelah laporan dikirim, berformat TKT-tanggal-nomor.</p>
            </div>
            <div class="card shine">
                <span style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">2</span>
                <h3>Lacak Status</h3>
                <p>Lihat tahap verifikasi, perbaikan, hingga selesai beserta riwayat lengkapnya.</p>
            </div>
            <div class="card shine">
                <span style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">3</span>
                <h3>Butuh Bantuan?</h3>
                <p>Hubungi Bagian Sarpras: <?= e(setting_value('campus_phone', '-')); ?> &middot; <?= e(setting_value('campus_email', '-')); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($code !== '' && $ticket === null): ?>
            <div class="public-alert public-alert-error">Tiket dengan kode tersebut tidak ditemukan.</div>
        <?php endif; ?>

        <?php if ($ticket !== null): ?>
            <div class="public-form-card shine">
                <div class="tracking-head">
                    <div>
                        <h2><?= e($ticket['title']); ?></h2>
                        <p><?= e($ticket['ticket_code']); ?> &middot; <?= e($ticket['category']); ?> &middot; <?= e(trim(($ticket['building_name'] ?? '-') . ' / ' . ($ticket['room_name'] ?? '-'))); ?></p>
                    </div>
                    <span class="badge <?= e(status_badge_class($ticket['status'])); ?>"><?= e($ticket['status']); ?></span>
                </div>

                <?php if ($ticket['status'] === 'Ditolak'): ?>
                    <div class="public-alert public-alert-error" style="margin-top:1rem;">Laporan ditolak. Silakan lihat catatan pada riwayat.</div>
                <?php else: ?>
                    <div class="stepper" style="margin-top:1.2rem;">
                        <?php for ($s = 1; $s <= 4; $s++): ?>
                            <span style="text-align:center;">
                                <span class="step-dot<?= $current >= $s ? ' on' : ''; ?>" style="display:inline-flex;"><?= $s; ?></span>
                                <small style="display:block;color:var(--muted);font-size:.7rem;margin-top:.3rem;"><?= e($labels[$s - 1]); ?></small>
                            </span>
                            <?php if ($s < 4): ?><span class="step-bar<?= $current > $s ? ' on' : ''; ?>"></span><?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <p class="tracking-desc"><?= e($ticket['description']); ?></p>
                <?php if ($ticket['estimated_completion'] !== null): ?>
                    <p class="tracking-eta">Estimasi selesai: <strong><?= e(format_tanggal($ticket['estimated_completion'])); ?></strong></p>
                <?php endif; ?>

                <div style="margin-top:1.2rem;display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap;">
                    <a class="btn btn-primary" href="<?= base_url('/lacak-tiket/' . urlencode($ticket['ticket_code'])); ?>">Lihat Pelacakan Live ala Kurir</a>
                    <button class="btn btn-secondary" type="button" data-code="<?= e($ticket['ticket_code']); ?>" onclick="copyTicketCode(this)">Salin Kode</button>
                </div>
            </div>

            <div class="vtimeline" style="max-width:660px; margin:2.2rem auto 0;">
                <span class="vt-line"><i style="height:100%;"></i></span>
                <?php $n = 1; foreach ($histories as $history): ?>
                    <div class="vt-item active">
                        <span class="vt-dot"><?= $n++; ?></span>
                        <div class="card vt-card">
                            <h3><?= e($history['new_status']); ?></h3>
                            <p><?= e(format_tanggal_waktu($history['created_at'])); ?></p>
                            <?php if (!empty($history['note'])): ?>
                                <p style="margin-top:.3rem;font-size:.85rem;"><?= e($history['note']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function copyTicketCode(btn) {
    var code = btn.getAttribute('data-code');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(code).then(function () {
            btn.textContent = 'Tersalin!';
            setTimeout(function () { btn.textContent = 'Salin Kode'; }, 1500);
        });
    }
}
</script>