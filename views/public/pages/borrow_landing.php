<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$order = ['Menunggu Verifikasi' => 1, 'Diverifikasi' => 2, 'Disetujui' => 3, 'Diserahkan' => 4, 'Dipinjam' => 4, 'Kembali' => 4, 'Selesai' => 4, 'Ditolak' => 0];
$current = $result !== null ? (int) ($order[$result['status']] ?? 1) : 0;
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Peminjaman</span>
        <h1>Peminjaman Barang &amp; Ruangan</h1>
        <p>Ajukan peminjaman barang inventaris atau ruangan kampus dengan alur persetujuan yang jelas dan transparan.</p>
        <p style="margin-top:.8rem;"><span class="badge badge-success"><span class="live-dot" style="margin-right:.4rem;"></span>Pelacakan status waktu nyata</span></p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $stats['borrows']; ?></span><span class="stat-label">Peminjaman Barang</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $stats['bookings']; ?></span><span class="stat-label">Peminjaman Ruangan</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $stats['approved']; ?></span><span class="stat-label">Telah Disetujui</span></span></div>
        </div>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <div class="cards-grid-2">
            <a class="flip" href="<?= base_url('/peminjaman/barang'); ?>">
                <div class="flip-inner">
                    <div class="flip-face flip-front">
                        <div class="bento-icon" style="position:absolute;top:1.2rem;left:1.2rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
                        </div>
                        <h3>Peminjaman Barang</h3>
                        <p>Proyektor, laptop, sound system, dan inventaris lainnya.</p>
                    </div>
                    <div class="flip-face flip-back">
                        <h3 style="color:var(--primary-dark);">Ajukan Sekarang</h3>
                        <p style="color:var(--muted);">Maksimal 3 jenis barang per pengajuan dengan persetujuan berjenjang dan kode pelacakan PJM-.</p>
                        <span class="service-link">Buka Formulir &rarr;</span>
                    </div>
                </div>
            </a>
            <a class="flip" href="<?= base_url('/peminjaman/ruangan'); ?>">
                <div class="flip-inner">
                    <div class="flip-face flip-front" style="background:linear-gradient(160deg,#f0b429,#b45309);">
                        <div class="bento-icon" style="position:absolute;top:1.2rem;left:1.2rem;background:rgba(255,255,255,.2);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                        </div>
                        <h3>Peminjaman Ruangan</h3>
                        <p>Aula, ruang kelas, dan laboratorium bersama.</p>
                    </div>
                    <div class="flip-face flip-back">
                        <h3 style="color:var(--primary-dark);">Cek Jadwal</h3>
                        <p style="color:var(--muted);">Sistem anti-bentrok jadwal otomatis dengan kode pelacakan RBM- dan log utilitas akreditasi.</p>
                        <span class="service-link">Buka Formulir &rarr;</span>
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

                <?php if ($result['status'] === 'Ditolak'): ?>
                    <div class="public-alert public-alert-error" style="margin-top:1rem;">Pengajuan ditolak. Silakan hubungi Bagian Sarpras untuk keterangan lebih lanjut.</div>
                <?php else: ?>
                    <div class="stepper" style="margin-top:1.2rem;">
                        <?php $labels = ['Verifikasi', 'Persetujuan', 'Penyerahan', 'Selesai']; ?>
                        <?php for ($s = 1; $s <= 4; $s++): ?>
                            <span style="text-align:center;">
                                <span class="step-dot<?= $current >= $s ? ' on' : ''; ?>" style="display:inline-flex;"><?= $s; ?></span>
                                <small style="display:block;color:var(--muted);font-size:.7rem;margin-top:.3rem;"><?= e($labels[$s - 1]); ?></small>
                            </span>
                            <?php if ($s < 4): ?><span class="step-bar<?= $current > $s ? ' on' : ''; ?>"></span><?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <p class="tracking-eta" style="margin-top:1rem;">Status persetujuan: <strong><?= e($result['approval']); ?></strong></p>
            </div>
        <?php endif; ?>

        <h2 class="section-title" style="margin-top:2.6rem;">Cara Kerja Peminjaman</h2>
        <div class="cards-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="card shine">
                <span style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">1</span>
                <h3>Ajukan</h3>
                <p>Isi formulir barang atau ruangan; sistem memeriksa ketersediaan secara otomatis.</p>
            </div>
            <div class="card shine">
                <span style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">2</span>
                <h3>Verifikasi &amp; Persetujuan</h3>
                <p>Petugas memverifikasi, pejabat berwenang menyetujui, lalu Anda menerima kode pelacakan.</p>
            </div>
            <div class="card shine">
                <span style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">3</span>
                <h3>Gunakan &amp; Kembalikan</h3>
                <p>Barang/ruangan dipakai sesuai jadwal, kemudian dikembalikan dan diperiksa kondisinya.</p>
            </div>
        </div>

        <div class="card shine" style="margin-top:1.4rem;display:flex;gap:1rem;align-items:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:34px;height:34px;color:var(--primary);flex-shrink:0;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            <p style="margin:0;">Seluruh barang diserahkan dalam kondisi teruji fungsi dan dicatat pada log pemeliharaan &mdash; keamanan dan kenyamanan Anda adalah prioritas kami.</p>
        </div>
    </div>
</section>