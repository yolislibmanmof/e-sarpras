<?php
$logo = setting_value('app_logo', '');
$logoPath = $logo !== '' ? rtrim(config('upload.base_path'), '/') . '/' . $logo : '';
?>
<footer class="site-footer">
    <div class="container footer-cols">
        <div class="footer-col">
            <div class="footer-logo">
                <?php if ($logoPath !== '' && is_file($logoPath)): ?>
                    <img src="<?= base_url('/media/' . $logo); ?>" alt="Logo" style="width:36px;height:36px;border-radius:10px;object-fit:cover;">
                <?php else: ?>
                    <span class="brand-mark">eS</span>
                <?php endif; ?>
                <span><?= e(setting_value('campus_name', config('app.name'))); ?></span>
            </div>
            <p>Sistem Informasi Manajemen Aset dan Operasional Sarana &amp; Prasarana. Transparan, akuntabel, dan siap akreditasi.</p>
        </div>

        <div class="footer-col">
            <h4>Layanan</h4>
            <a href="<?= base_url('/lapor-kerusakan'); ?>">Lapor Kerusakan</a>
            <a href="<?= base_url('/lacak-laporan'); ?>">Lacak Laporan</a>
            <a href="<?= base_url('/peminjaman'); ?>">Peminjaman</a>
            <a href="<?= base_url('/permintaan-barang'); ?>">Permintaan Barang</a>
            <a href="<?= base_url('/survei'); ?>">Survei Kepuasan</a>
        </div>

        <div class="footer-col">
            <h4>Informasi</h4>
            <a href="<?= base_url('/gedung'); ?>">Gedung &amp; Ruang</a>
            <a href="<?= base_url('/'); ?>">Beranda</a>
            <a href="<?= admin_url('/login'); ?>">Login Admin</a>
        </div>

        <div class="footer-col">
            <h4>Kontak</h4>
            <p><?= e(setting_value('campus_address', 'Alamat kampus belum diatur.')); ?></p>
            <p>Telp: <?= e(setting_value('campus_phone', '-')); ?></p>
            <p>Email: <?= e(setting_value('campus_email', '-')); ?></p>
            <p>Jam layanan: Senin&ndash;Jumat, 08.00&ndash;16.00</p>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">&copy; <?= date('Y'); ?> <?= e(setting_value('campus_name', config('app.name'))); ?> &mdash; Bagian Sarana dan Prasarana.</div>
    </div>
</footer>