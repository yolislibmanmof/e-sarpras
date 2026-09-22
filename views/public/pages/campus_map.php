<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php
$dbM = \App\Core\Database::instance();
$bRow = $dbM->selectOne('SELECT * FROM buildings WHERE id = ?', [$bId]);
$activeRooms = 0; $accRooms = 0;
foreach ($rooms as $r) {
    if ($r['status'] === 'Aktif') { $activeRooms++; }
    if ((int) $r['is_disability_friendly'] === 1) { $accRooms++; }
}
?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Peta Kampus</span>
        <h1>Peta Interaktif Kampus</h1>
        <p>Jelajahi denah gedung dan ruangan secara visual. Klik ruangan untuk melihat profil, foto, dan jadwalnya.</p>
        <p style="margin-top:.8rem;"><span class="badge badge-success"><span class="live-dot" style="margin-right:.4rem;"></span><?= count($buildings); ?> gedung &middot; <?= count($rooms); ?> ruangan pada pilihan ini</span></p>
    </div>
</section>

<section class="section" style="padding:2.4rem 0 0;">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card shine"><span><span class="stat-value"><?= count($buildings); ?></span><span class="stat-label">Gedung</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= count($rooms); ?></span><span class="stat-label">Ruangan Terpilih</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $activeRooms; ?></span><span class="stat-label">Ruangan Aktif</span></span></div>
            <div class="stat-card shine"><span><span class="stat-value"><?= (int) $accRooms; ?></span><span class="stat-label">Ruangan Aksesibel</span></span></div>
        </div>
    </div>
</section>

<section class="section page-body">
    <div class="container">

        <!-- Pemilih gedung -->
        <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem;">
            <?php foreach ($buildings as $b): ?>
                <a class="btn <?= (int) $b['id'] === $bId ? 'btn-primary' : 'btn-secondary'; ?> btn-sm" href="<?= base_url('/peta?b=' . (int) $b['id']); ?>"><?= e($b['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <!-- Pemilih lantai -->
        <?php if ($floors !== []): ?>
        <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1.4rem;">
            <a class="badge <?= $fLevel === 0 ? 'badge-success' : 'badge-info'; ?>" href="<?= base_url('/peta?b=' . $bId); ?>">Semua Lantai</a>
            <?php foreach ($floors as $fl): ?>
                <a class="badge <?= $fLevel === (int) $fl['level'] ? 'badge-success' : 'badge-info'; ?>" href="<?= base_url('/peta?b=' . $bId . '&f=' . (int) $fl['level']); ?>">L<?= (int) $fl['level']; ?> &middot; <?= e($fl['name']); ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Panel informasi gedung -->
        <?php if ($bRow !== null): ?>
        <div class="card shine" style="margin-bottom:1.4rem;display:flex;gap:1.2rem;align-items:center;flex-wrap:wrap;">
            <?php if (!empty($bRow['photo'])): ?>
                <img src="<?= base_url('/media/' . $bRow['photo']); ?>" alt="<?= e($bRow['name']); ?>" style="width:120px;height:90px;object-fit:cover;border-radius:12px;flex-shrink:0;">
            <?php endif; ?>
            <div style="flex:1;min-width:220px;">
                <h3 style="margin:0;"><?= e($bRow['name']); ?></h3>
                <p style="margin:.3rem 0 0;font-size:.86rem;"><?= e($bRow['address'] ?? 'Alamat belum dicantumkan.'); ?></p>
                <p style="margin:.4rem 0 0;">
                    <span class="badge <?= e(status_badge_class($bRow['condition'])); ?>"><?= e($bRow['condition']); ?></span>
                    <?php if ((int) $bRow['is_disability_friendly'] === 1): ?><span class="badge badge-success">Ramah Disabilitas</span><?php endif; ?>
                </p>
            </div>
            <a class="btn btn-secondary btn-sm" href="<?= base_url('/gedung/' . (int) $bRow['id']); ?>">Profil Gedung</a>
        </div>
        <?php endif; ?>

        <!-- Legenda + pencarian -->
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.2rem;">
            <div style="display:flex;flex-wrap:wrap;gap:1rem;font-size:.8rem;color:var(--muted);">
                <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#16a34a;margin-right:.35rem;"></i>Aktif</span>
                <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#d97706;margin-right:.35rem;"></i>Dalam Perbaikan</span>
                <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#94a3b8;margin-right:.35rem;"></i>Tidak Aktif</span>
            </div>
            <input id="roomFilter" type="text" placeholder="Saring kode / nama ruangan..." style="max-width:260px;padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:999px;font-size:.85rem;background:var(--card);color:var(--text);">
        </div>

        <?php if ($mapSvg !== null && $mapSvg !== ''): ?>
        <!-- Denah SVG unggahan admin (elemen ber-atribut data-room dapat diklik) -->
        <div class="card shine" style="margin-bottom:1.6rem;padding:1rem;">
            <div id="mapSvgWrap" style="width:100%;overflow:auto;"><?= $mapSvg; ?></div>
        </div>
        <script>
        (function(){
            var map = <?= json_encode($roomMap); ?>;
            document.querySelectorAll('#mapSvgWrap [data-room]').forEach(function(el){
                var id = map[el.getAttribute('data-room')];
                if (!id) { return; }
                el.style.cursor = 'pointer';
                el.addEventListener('click', function(){ window.location.href = '<?= base_url('/ruangan/'); ?>' + id; });
            });
        })();
        </script>
        <?php else: ?>
        <div class="public-alert public-alert-success" style="margin-bottom:1.2rem;">
            Denah visual belum tersedia untuk pilihan ini &mdash; gunakan denah skematik interaktif di bawah.
        </div>
        <?php endif; ?>

        <!-- Denah skematik otomatis -->
        <?php if ($rooms === []): ?>
            <div class="public-alert public-alert-error">Belum ada ruangan pada pilihan ini.</div>
        <?php else: ?>
            <div class="cards-grid" id="mapTiles" style="grid-template-columns:repeat(auto-fill,minmax(180px,1fr));">
                <?php foreach ($rooms as $room): ?>
                    <?php
                    $color = $room['status'] === 'Aktif' ? '#16a34a' : ($room['status'] === 'Dalam Perbaikan' ? '#d97706' : '#94a3b8');
                    ?>
                    <a class="card bento shine map-tile" href="<?= base_url('/ruangan/' . (int) $room['id']); ?>" data-search="<?= e(strtolower($room['code'] . ' ' . $room['name'])); ?>" style="border-top:4px solid <?= $color; ?>;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <h3 style="font-size:.95rem;margin:0;"><?= e($room['code']); ?></h3>
                            <span style="width:10px;height:10px;border-radius:50%;background:<?= $color; ?>;display:inline-block;"></span>
                        </div>
                        <p style="margin-top:.4rem;"><?= e($room['name']); ?></p>
                        <p style="margin-top:.5rem;font-size:.76rem;">
                            <?= $room['floor_level'] !== null ? 'L' . (int) $room['floor_level'] : 'Tanpa lantai'; ?> &middot; Kap. <?= (int) $room['capacity']; ?>
                            <?php if ((int) $room['is_disability_friendly'] === 1): ?> &middot; &#9855;<?php endif; ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var input = document.getElementById('roomFilter');
    var wrap = document.getElementById('mapTiles');
    if (!input || !wrap) { return; }
    input.addEventListener('input', function () {
        var q = input.value.trim().toLowerCase();
        wrap.querySelectorAll('.map-tile').forEach(function (tile) {
            tile.style.display = (q === '' || tile.getAttribute('data-search').indexOf(q) !== -1) ? '' : 'none';
        });
    });
})();
</script>