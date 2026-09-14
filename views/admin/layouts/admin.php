<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin'); ?> | <?= e(config('app.name')); ?></title>
    <script>try{if(localStorage.getItem('es_theme')==='dark'){document.documentElement.setAttribute('data-theme','dark');}}catch(e){}</script>
    <?php
    $fav = setting_value('app_favicon', '');
    $favType = '';
    if ($fav !== '') {
        $ext = strtolower(pathinfo($fav, PATHINFO_EXTENSION));
        $favType = ['ico' => 'image/x-icon', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'svg' => 'image/svg+xml'][$ext] ?? '';
        $favUrl = e(base_url('/media/' . $fav));
    } else {
        $favType = 'image/svg+xml';
        $favUrl = 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#0f6f5c"/><text x="32" y="44" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="#ffffff" text-anchor="middle">eS</text></svg>');
    }
    ?>
    <link rel="icon" type="<?= e($favType); ?>" href="<?= $favUrl; ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/sidebar.css'); ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/topbar.css'); ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/components.css'); ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/dashboard.css'); ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/charts.css'); ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/tables.css'); ?>">
    <link rel="stylesheet" href="<?= admin_asset_url('css/forms.css'); ?>">
</head>
<body class="admin-body">
    <div id="admin-loader"><div class="ld">eS</div></div>

    <svg width="0" height="0" style="position:absolute"><defs><linearGradient id="gradSpark" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#0f6f5c"/><stop offset="1" stop-color="#2dd4bf"/></linearGradient></defs></svg>

    <div class="admin-wrapper">
        <?php require base_path('views/admin/layouts/sidebar.php'); ?>

        <div class="admin-main">
            <?php require base_path('views/admin/layouts/topbar.php'); ?>

            <main class="admin-content">
                <?php $success = \App\Core\Session::getFlash('success'); ?>
                <?php if ($success !== null): ?>
                    <div class="flash flash-success"><?= e($success); ?></div>
                <?php endif; ?>
                <?php $error = \App\Core\Session::getFlash('error'); ?>
                <?php if ($error !== null): ?>
                    <div class="flash flash-error"><?= e($error); ?></div>
                <?php endif; ?>

                <?= $content ?? ''; ?>
            </main>
        </div>
    </div>

    <div class="fab-wrap" id="fab">
        <button class="fab-main" id="fabMain" type="button" title="Buat cepat">+</button>
        <a class="fab-item" href="<?= admin_url('/aset/tambah'); ?>">+ Aset</a>
        <a class="fab-item" href="<?= admin_url('/surat/keluar/tambah'); ?>">+ Surat</a>
        <a class="fab-item" href="<?= admin_url('/pemeliharaan/tambah'); ?>">+ Jadwal</a>
        <a class="fab-item" href="<?= admin_url('/stok/tambah'); ?>">+ Stok</a>
    </div>

    <div class="palette" id="palette" style="position:fixed;inset:0;background:rgba(4,35,29,.45);backdrop-filter:blur(6px);z-index:999;display:none;align-items:flex-start;justify-content:center;padding-top:12vh;">
        <div style="width:min(560px,92%);background:var(--card);color:var(--ink);border-radius:18px;box-shadow:var(--shadow-lg);overflow:hidden;">
            <input id="paletteInput" placeholder="Ketik modul atau halaman..." style="border:none;border-radius:0;padding:1rem 1.2rem;font-size:1rem;background:var(--card);color:var(--ink);">
            <div id="paletteList" style="max-height:320px;overflow-y:auto;padding:.5rem;">
                <?php $nav = ['Dashboard'=>'/dashboard','Gedung'=>'/gedung','Ruangan'=>'/ruangan','Aset'=>'/aset','Kategori Aset'=>'/aset-kategori','Tiket Kerusakan'=>'/tiket','Pemeliharaan'=>'/pemeliharaan','Log Pemeliharaan'=>'/pemeliharaan/log','Peminjaman Barang'=>'/peminjaman-barang','Peminjaman Ruangan'=>'/peminjaman-ruangan','Permintaan Barang'=>'/permintaan-barang','Stok Gudang'=>'/stok','Kategori Stok'=>'/stok-kategori','Teknisi'=>'/teknisi','Vendor'=>'/vendor','APAR'=>'/k3/apar','Limbah'=>'/k3/limbah','Simulasi Bencana'=>'/k3/simulasi','Surat & Disposisi'=>'/surat','Template Surat'=>'/template-surat','Survei'=>'/survei','Laporan'=>'/laporan','Pengaturan'=>'/pengaturan','Audit Log'=>'/audit-log','Backup'=>'/backup']; ?>
                <?php foreach ($nav as $label => $path): ?>
                    <a href="<?= admin_url($path); ?>" style="display:flex;padding:.6rem .9rem;border-radius:10px;font-size:.9rem;"><?= e($label); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <style>.palette.on{display:flex!important}</style>

    <script src="<?= admin_asset_url('js/admin.js'); ?>"></script>
    <script src="<?= admin_asset_url('js/charts.js'); ?>"></script>
</body>
</html>