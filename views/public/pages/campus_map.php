<?php require base_path('views/public/partials/wow_style.php'); ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/'); ?>">Beranda</a> &rarr; Peta Kampus</span>
        <h1>Peta Interaktif Kampus</h1>
        <p>Jelajahi denah gedung dan ruangan secara visual. Klik ruangan untuk melihat profil, foto, dan jadwalnya.</p>
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

        <!-- Legenda -->
        <div style="display:flex;flex-wrap:wrap;gap:1rem;margin-bottom:1.2rem;font-size:.8rem;color:var(--muted);">
            <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#16a34a;margin-right:.35rem;"></i>Aktif</span>
            <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#d97706;margin-right:.35rem;"></i>Dalam Perbaikan</span>
            <span><i style="display:inline-block;width:12px;height:12px;border-radius:4px;background:#94a3b8;margin-right:.35rem;"></i>Tidak Aktif</span>
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
        <?php endif; ?>

        <!-- Denah skematik otomatis -->
        <?php if ($rooms === []): ?>
            <div class="public-alert public-alert-error">Belum ada ruangan pada pilihan ini.</div>
        <?php else: ?>
            <div class="cards-grid" style="grid-template-columns:repeat(auto-fill,minmax(180px,1fr));">
                <?php foreach ($rooms as $room): ?>
                    <?php
                    $color = $room['status'] === 'Aktif' ? '#16a34a' : ($room['status'] === 'Dalam Perbaikan' ? '#d97706' : '#94a3b8');
                    ?>
                    <a class="card bento shine" href="<?= base_url('/ruangan/' . (int) $room['id']); ?>" style="border-top:4px solid <?= $color; ?>;">
                        <h3 style="font-size:.95rem;"><?= e($room['code']); ?></h3>
                        <p><?= e($room['name']); ?></p>
                        <p style="margin-top:.5rem;font-size:.76rem;">
                            <?= $room['floor_level'] !== null ? 'L' . (int) $room['floor_level'] : 'Tanpa lantai'; ?> &middot; Kap. <?= (int) $room['capacity']; ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>