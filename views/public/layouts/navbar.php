<?php
$uri = current_uri();
$isService = (strpos($uri, '/lapor-kerusakan') === 0 || strpos($uri, '/lacak-laporan') === 0 || strpos($uri, '/peminjaman') === 0 || strpos($uri, '/permintaan-barang') === 0);
$logo = setting_value('app_logo', '');
$logoPath = $logo !== '' ? rtrim(config('upload.base_path'), '/') . '/' . $logo : '';
?>
<header class="navbar">
    <div class="container navbar-inner">
        <a class="navbar-brand" href="<?= base_url('/'); ?>">
            <?php if ($logoPath !== '' && is_file($logoPath)): ?>
                <img src="<?= base_url('/media/' . $logo); ?>" alt="Logo" style="width:40px;height:40px;border-radius:12px;object-fit:cover;box-shadow:0 6px 16px rgba(15,111,92,.35);">
            <?php else: ?>
                <span class="brand-mark">eS</span>
            <?php endif; ?>
            <span class="brand-text"><?= e(setting_value('campus_name', config('app.name'))); ?></span>
        </a>

        <button class="navbar-toggle" id="navbarToggle" type="button" aria-label="Buka menu">
            <span></span><span></span><span></span>
        </button>

        <nav class="navbar-menu" id="navbarMenu">
            <a class="nav-link<?= $uri === '/' ? ' active' : ''; ?>" href="<?= base_url('/'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                Beranda
            </a>

            <a class="nav-link<?= strpos($uri, '/gedung') === 0 ? ' active' : ''; ?>" href="<?= base_url('/gedung'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M12 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M12 10h.01"/><path d="M16 10h.01"/></svg>
                Gedung &amp; Ruang
            </a>

            <div class="nav-drop<?= $isService ? ' active-drop' : ''; ?>">
                <button type="button" class="nav-link nav-drop-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7"/><path d="M4 10V3"/><path d="M12 21v-9"/><path d="M12 8V3"/><path d="M20 21v-5"/><path d="M20 12V3"/><path d="M1 14h6"/><path d="M9 8h6"/><path d="M17 16h6"/></svg>
                    Layanan
                    <span class="drop-caret"></span>
                </button>
                <div class="nav-drop-panel">
                    <a class="drop-item" href="<?= base_url('/lapor-kerusakan'); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.3 3.8 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0z"/></svg>
                        <span><b>Lapor Kerusakan</b><small>Kirim aduan fasilitas</small></span>
                    </a>
                    <a class="drop-item" href="<?= base_url('/lacak-laporan'); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <span><b>Lacak Laporan</b><small>Pantau status tiket</small></span>
                    </a>
                    <a class="drop-item" href="<?= base_url('/peminjaman'); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                        <span><b>Peminjaman</b><small>Barang &amp; ruangan</small></span>
                    </a>
                    <a class="drop-item" href="<?= base_url('/permintaan-barang'); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                        <span><b>Permintaan Barang</b><small>ATK &amp; elektronik</small></span>
                    </a>
                </div>
            </div>

            <a class="nav-link<?= strpos($uri, '/survei') === 0 ? ' active' : ''; ?>" href="<?= base_url('/survei'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Survei
            </a>

            <a class="nav-link<?= strpos($uri, '/transparansi') === 0 ? ' active' : ''; ?>" href="<?= base_url('/transparansi'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Transparansi
            </a>

            <a class="nav-link-admin" href="<?= admin_url('/login'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/></svg>
                Login Admin
            </a>
        </nav>
    </div>
</header>