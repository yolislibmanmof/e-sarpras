<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(($title ?? 'Beranda') . ' - ' . config('app.name')); ?></title>
    <meta name="description" content="Sistem Informasi Sarana dan Prasarana Kampus">
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
    <link rel="stylesheet" href="<?= asset_url('css/main.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/layout.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/navbar.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/components.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/home.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/pages.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/forms.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/ticket.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/responsive.css'); ?>">
    <link rel="stylesheet" href="<?= asset_url('css/ultimate.css'); ?>">
</head>
<body>
    <?php require base_path('views/public/layouts/navbar.php'); ?>

    <main class="site-main">
        <?php $success = \App\Core\Session::getFlash('success'); ?>
        <?php if ($success !== null): ?>
            <div class="container"><div class="public-alert public-alert-success"><?= e($success); ?></div></div>
        <?php endif; ?>

        <?php $error = \App\Core\Session::getFlash('error'); ?>
        <?php if ($error !== null): ?>
            <div class="container"><div class="public-alert public-alert-error"><?= e($error); ?></div></div>
        <?php endif; ?>

        <?= $content ?? ''; ?>
    </main>

    <?php require base_path('views/public/layouts/footer.php'); ?>

    <script src="<?= asset_url('js/main.js'); ?>"></script>
    <script src="<?= asset_url('js/ticket-form.js'); ?>"></script>
    <script src="<?= asset_url('js/ultimate.js'); ?>"></script>
</body>
</html>