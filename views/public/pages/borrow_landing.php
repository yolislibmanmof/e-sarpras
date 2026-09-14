<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Peminjaman</span>
        <h1>Peminjaman Barang &amp; Ruangan</h1>
        <p>Ajukan peminjaman barang inventaris atau ruangan kampus dengan alur persetujuan yang jelas dan transparan.</p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="stat-card shine"><span class="stat-value"><?= (int) $stats['borrows']; ?></span><span class="stat-label">Peminjaman Barang</span></div>
            <div class="stat-card shine"><span class="stat-value"><?= (int) $stats['bookings']; ?></span><span class="stat-label">Peminjaman Ruangan</span></div>
            <div class="stat-card shine"><span class="stat-value"><?= (int) $stats['approved']; ?></span><span class="stat-label">Telah Disetujui</span></div>
        </div>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <div class="cards-grid-2">
            <a class="flip" href="<?= base_url('/peminjaman/barang'); ?>">
                <div class="flip-inner">
                    <div class="flip-face flip-front">
                        <h3>Peminjaman Barang</h3>
                        <p>Proyektor, laptop, sound system, dan inventaris lainnya.</p>
                    </div>
                    <div class="flip-face flip-back">
                        <h3 style="color:var(--primary-dark);">Ajukan Sekarang</h3>
                        <p style="color:var(--muted);">Maksimal 3 jenis barang per pengajuan dengan persetujuan berjenjang dan kode pelacakan PJM-.</p>
                        <span class="service-link">Buka Formulir</span>
                    </div>
                </div>
            </a>
            <a class="flip" href="<?= base_url('/peminjaman/ruangan'); ?>">
                <div class="flip-inner">
                    <div class="flip-face flip-front" style="background:linear-gradient(160deg,#f0b429,#b45309);">
                        <h3>Peminjaman Ruangan</h3>
                        <p>Aula, ruang kelas, dan laboratorium bersama.</p>
                    </div>
                    <div class="flip-face flip-back">
                        <h3 style="color:var(--primary-dark);">Cek Jadwal</h3>
                        <p style="color:var(--muted);">Sistem anti-bentrok jadwal otomatis dengan kode pelacakan RBM- dan log utilitas akreditasi.</p>
                        <span class="service-link">Buka Formulir</span>
                    </div>
                </div>
            </a>
        </div>

        <form method="GET" action="<?= base_url('/peminjaman'); ?>" class="tracking-form">
            <input type="text" name="code" value="<?= e($code); ?>" placeholder="Kode peminjaman (PJM-/RBM-)" required>
            <button type="submit" class="btn btn-primary">Lacak</button>
        </form>

        <?php if ($code !== '' && $result === null): ?>
            <div class="public-alert public-alert-error">Kode peminjaman tidak ditemukan.</div>
        <?php endif; ?>

        <?php if ($result !== null): ?>
            <div class="public-form-card shine">
                <div class="tracking-head">
                    <div>
                        <h2><?= e($result['title']); ?></h2>
                        <p><?= e($result['type']); ?> &middot; <?= e($result['code']); ?></p>
                    </div>
                    <span class="badge <?= e(status_badge_class($result['status'])); ?>"><?= e($result['status']); ?></span>
                </div>
                <p class="tracking-eta">Status persetujuan: <strong><?= e($result['approval']); ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</section>