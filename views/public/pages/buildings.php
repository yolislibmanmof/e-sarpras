<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php $names = array_column($rows, 'name'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Gedung &amp; Ruang</span>
        <h1>Gedung &amp; Ruang Kampus</h1>
        <p>Jelajahi profil gedung, ruangan, kondisi, dan fasilitas aksesibilitas yang dikelola Bagian Sarana dan Prasarana.</p>
        <p style="margin-top:.8rem;"><span class="badge badge-success"><span class="live-dot" style="margin-right:.4rem;"></span><?= (int) $stats['buildings']; ?> gedung &middot; <?= (int) $stats['rooms']; ?> ruangan terpantau</span></p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $stats['buildings']; ?></span><span class="stat-label">Gedung</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $stats['rooms']; ?></span><span class="stat-label">Ruangan</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $stats['accessible']; ?></span><span class="stat-label">Gedung Aksesibel</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $stats['assets']; ?></span><span class="stat-label">Aset Tercatat</span></span></div>
        </div>
    </div>
</section>

<?php if ($names !== []): ?>
<section style="padding:1.6rem 0 0;">
    <div class="container">
        <div class="ticker">
            <div class="ticker-track">
                <?php for ($r = 0; $r < 2; $r++): foreach ($names as $n): ?>
                    <span class="ticker-item">&#10022; <b><?= e($n); ?></b></span>
                <?php endforeach; endfor; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section page-body">
    <div class="container">
        <form method="GET" action="<?= base_url('/gedung'); ?>" class="tracking-form" style="margin-top:0;">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari nama atau kode gedung...">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        <!-- Filter kondisi interaktif -->
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin:1rem 0 0;" id="condChips">
            <button class="badge badge-info" data-f="" type="button" style="cursor:pointer;">Semua Kondisi</button>
            <?php foreach (['Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'] as $c): ?>
                <button class="badge <?= e(status_badge_class($c)); ?>" data-f="<?= e($c); ?>" type="button" style="cursor:pointer;"><?= e($c); ?></button>
            <?php endforeach; ?>
        </div>

        <?php if ($rows === []): ?>
            <div class="public-alert public-alert-error">Tidak ada gedung yang cocok dengan pencarian Anda.</div>
        <?php else: ?>
            <div class="cards-grid" id="bCards" style="margin-top:1.2rem;">
                <?php foreach ($rows as $row): ?>
                    <div class="card bento shine" data-cond="<?= e($row['condition']); ?>">
                        <?php if (!empty($row['photo'])): ?>
                            <img src="<?= base_url('/media/' . $row['photo']); ?>" alt="<?= e($row['name']); ?>" style="width:100%;height:150px;object-fit:cover;border-radius:14px;margin-bottom:1rem;box-shadow:var(--shadow);">
                        <?php endif; ?>
                        <div class="bento-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M12 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M12 10h.01"/><path d="M16 10h.01"/></svg>
                        </div>
                        <h3><?= e($row['name']); ?></h3>
                        <p>Kode <?= e($row['code']); ?> &middot; <?= (int) $row['room_count']; ?> ruangan &middot; Tahun <?= e($row['year_built'] ?? '-'); ?></p>
                        <p style="margin:0.7rem 0;">
                            <span class="badge <?= e(status_badge_class($row['condition'])); ?>"><?= e($row['condition']); ?></span>
                            <?php if ((int) $row['is_disability_friendly'] === 1): ?>
                                <span class="badge badge-success">Ramah Disabilitas</span>
                            <?php endif; ?>
                        </p>
                        <div style="display:flex;gap:1rem;margin-top:.4rem;flex-wrap:wrap;">
                            <a class="service-link" href="<?= base_url('/gedung/' . $row['id']); ?>">Lihat Detail</a>
                            <a class="service-link" href="<?= base_url('/peta?b=' . (int) $row['id']); ?>">Peta</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ================= DAFTAR RUANGAN UNTUK PUBLIK ================= -->
<section class="section" style="padding-top:0.5rem;">
    <div class="container">
        <h2 class="section-title">Jelajahi Ruangan</h2>
        <p class="section-lead">Ruangan yang dapat Anda lihat profilnya, lengkap dengan foto, jenis, kapasitas, kondisi, dan jadwal ketersediaan.</p>

        <?php if (empty($rooms)): ?>
            <div class="public-alert public-alert-error">Belum ada ruangan yang dipublikasikan.</div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($rooms as $room): ?>
                    <div class="card bento shine">
                        <?php if (!empty($room['photo'])): ?>
                            <img src="<?= base_url('/media/' . $room['photo']); ?>" alt="<?= e($room['name']); ?>" style="width:100%;height:140px;object-fit:cover;border-radius:14px;margin-bottom:1rem;box-shadow:var(--shadow);">
                        <?php endif; ?>
                        <div class="bento-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-4h6v4"/></svg>
                        </div>
                        <h3><?= e($room['code']); ?> - <?= e($room['name']); ?></h3>
                        <p><?= e($room['building_name'] ?? '-'); ?> &middot; <?= e($room['room_type']); ?></p>
                        <p style="margin:0.7rem 0;">
                            <span class="badge <?= e(status_badge_class($room['condition'])); ?>"><?= e($room['condition']); ?></span>
                            <span class="badge badge-info">Kapasitas <?= (int) $room['capacity']; ?></span>
                            <?php if ((int) $room['is_disability_friendly'] === 1): ?>
                                <span class="badge badge-success">Aksesibel</span>
                            <?php endif; ?>
                        </p>
                        <div style="display:flex;gap:1rem;margin-top:.4rem;flex-wrap:wrap;">
                            <a class="service-link" href="<?= base_url('/ruangan/' . (int) $room['id']); ?>">Lihat Detail</a>
                            <a class="service-link" href="<?= base_url('/ruangan/' . (int) $room['id'] . '/jadwal'); ?>">Lihat Jadwal</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var wrap = document.getElementById('bCards');
    var chips = document.querySelectorAll('#condChips button');
    if (!wrap) { return; }
    chips.forEach(function (ch) {
        ch.addEventListener('click', function () {
            var f = ch.getAttribute('data-f');
            wrap.querySelectorAll('[data-cond]').forEach(function (card) {
                card.style.display = (f === '' || card.getAttribute('data-cond') === f) ? '' : 'none';
            });
            chips.forEach(function (c) {
                c.style.outline = (c === ch) ? '2px solid var(--accent-2)' : 'none';
                c.style.outlineOffset = '2px';
            });
        });
    });
})();
</script>