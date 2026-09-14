<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Survei</span>
        <h1>Survei Kepuasan Sarpras</h1>
        <p>Sampaikan penilaian Anda terhadap fasilitas kampus. Hasil survei menjadi dasar perbaikan berkelanjutan.</p>
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
                                <h3><?= e($survey['title']); ?></h3>
                                <p><?= e(str_limit($survey['description'] ?? 'Isi survei untuk membantu peningkatan layanan.', 80)); ?></p>
                            </div>
                            <div class="flip-face flip-back">
                                <h3 style="color:var(--primary-dark);"><?= (int) $survey['response_count']; ?> Respons</h3>
                                <p style="color:var(--muted);">Balik kartu ini dan klik untuk memberikan penilaian Anda. Anonim dan aman.</p>
                                <span class="service-link">Isi Survei</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>