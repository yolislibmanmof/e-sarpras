<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$order = ['Menunggu Verifikasi' => 1, 'Diverifikasi' => 2, 'Menunggu Sparepart' => 3, 'Sedang Diperbaiki' => 3, 'Selesai' => 4, 'Ditolak' => 0];
$current = $ticket !== null ? (int) ($order[$ticket['status']] ?? 1) : 0;
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Lacak Laporan</span>
        <h1>Lacak Laporan</h1>
        <p>Masukkan kode tiket Anda untuk melihat status penanganan laporan secara langsung.</p>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <form method="GET" action="<?= base_url('/lacak-laporan'); ?>" class="tracking-form">
            <input type="text" name="code" value="<?= e($code); ?>" placeholder="Contoh: TKT-20260825-0001" required>
            <button type="submit" class="btn btn-primary">Lacak</button>
        </form>

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
                    <div class="stepper">
                        <?php for ($s = 1; $s <= 4; $s++): ?>
                            <span class="step-dot<?= $current >= $s ? ' on' : ''; ?>"><?= $s; ?></span>
                            <?php if ($s < 4): ?><span class="step-bar<?= $current > $s ? ' on' : ''; ?>"></span><?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <p class="tracking-desc"><?= e($ticket['description']); ?></p>
                <?php if ($ticket['estimated_completion'] !== null): ?>
                    <p class="tracking-eta">Estimasi selesai: <strong><?= e(format_tanggal($ticket['estimated_completion'])); ?></strong></p>
                <?php endif; ?>

                <div style="margin-top:1.2rem;text-align:center;">
                    <a class="btn btn-primary" href="<?= base_url('/lacak-tiket/' . urlencode($ticket['ticket_code'])); ?>">
                        Lihat Pelacakan Live ala Kurir
                    </a>
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
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>