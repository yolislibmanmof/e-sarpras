<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$dbS = \App\Core\Database::instance();
$totalResponses = 0;
foreach ($surveys as $s) { $totalResponses += (int) $s['response_count']; }
$avgRow = $dbS->selectOne(
    "SELECT AVG(sa.rating_value) AS avg_rating
     FROM survey_answers sa
     JOIN survey_questions sq ON sq.id = sa.survey_question_id
     JOIN surveys s ON s.id = sq.survey_id
     WHERE s.is_active = 1 AND sa.rating_value IS NOT NULL"
);
$avgRating = $avgRow !== null ? (float) ($avgRow['avg_rating'] ?? 0) : 0.0;
$satPct = $avgRating > 0 ? min(100, round($avgRating / 5 * 100)) : 0;
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Survei</span>
        <h1>Survei Kepuasan Sarpras</h1>
        <p>Sampaikan penilaian Anda terhadap fasilitas kampus. Hasil survei menjadi dasar perbaikan berkelanjutan.</p>
        <p style="margin-top:.8rem;"><span class="badge badge-success"><span class="live-dot" style="margin-right:.4rem;"></span><?= count($surveys); ?> survei sedang dibuka</span></p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="stat-card shine"><span><span class="stat-value"><?= count($surveys); ?></span><span class="stat-label">Survei Aktif</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= number_format($totalResponses); ?></span><span class="stat-label">Total Respons</span></span></div>
            <div class="stat-card shine" style="display:flex;align-items:center;gap:1rem;">
                <div class="ring" style="--off:<?= (int) (264 * (1 - $satPct / 100)); ?>">
                    <svg width="92" height="92"><circle class="bgc" cx="46" cy="46" r="42"/><circle class="fgc" cx="46" cy="46" r="42"/></svg>
                    <div class="val"><?= $avgRating > 0 ? number_format($avgRating, 1) : '-'; ?><small>dari 5.0</small></div>
                </div>
                <span class="stat-label">Kepuasan Sivitas</span>
            </div>
        </div>
    </div>
</section>

<section class="section page-body">
    <div class="container">
        <?php if ($surveys === []): ?>
            <div class="public-alert public-alert-error">Saat ini belum ada survei aktif.</div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($surveys as $survey): ?>
                    <a class="flip" href="<?= base_url('/survei/' . $survey['slug']); ?>">
                        <div class="flip-inner">
                            <div class="flip-face flip-front">
                                <div class="bento-icon" style="position:absolute;top:1.2rem;left:1.2rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                </div>
                                <h3><?= e($survey['title']); ?></h3>
                                <p><?= e(str_limit($survey['description'] ?? 'Isi survei untuk membantu peningkatan layanan.', 80)); ?></p>
                                <p style="margin-top:.8rem;"><span class="badge badge-success"><?= (int) $survey['response_count']; ?> respons</span></p>
                            </div>
                            <div class="flip-face flip-back">
                                <h3 style="color:var(--primary-dark);">Mari Beri Penilaian</h3>
                                <p style="color:var(--muted);">Anonim, aman, dan berdampak langsung pada perbaikan fasilitas kampus.</p>
                                <span class="service-link">Isi Survei &rarr;</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section" style="padding-top:.5rem;">
    <div class="container">
        <h2 class="section-title">Cara Berpartisipasi</h2>
        <div class="cards-grid" style="grid-template-columns:repeat(3,1fr);">
            <div class="card shine">
                <span class="sv-num" style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">1</span>
                <h3>Pilih Survei</h3>
                <p>Tentukan survei yang sesuai dengan pengalaman Anda menggunakan fasilitas kampus.</p>
            </div>
            <div class="card shine">
                <span class="sv-num" style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">2</span>
                <h3>Isi Secara Anonim</h3>
                <p>Tanpa wajib login maupun mengisi nama. Jawaban Anda terlindungi dan hanya dipakai secara agregat.</p>
            </div>
            <div class="card shine">
                <span class="sv-num" style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent-2));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:.8rem;">3</span>
                <h3>Dampak Nyata</h3>
                <p>Hasil survei dianalisis tiap semester dan menjadi dasar prioritas perbaikan sarana &amp; prasarana.</p>
            </div>
        </div>

        <div class="card shine" style="margin-top:1.4rem;display:flex;gap:1rem;align-items:center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:34px;height:34px;color:var(--primary);flex-shrink:0;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <p style="margin:0;">Identitas Anda tidak pernah disimpan bersama jawaban. Data ditampilkan hanya dalam bentuk rekapitulasi pada <a href="<?= base_url('/transparansi'); ?>" style="color:var(--primary);font-weight:700;">Dashboard Transparansi</a>.</p>
        </div>
    </div>
</section>