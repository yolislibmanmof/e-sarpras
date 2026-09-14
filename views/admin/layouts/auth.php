<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin'); ?> | <?= e(config('app.name')); ?></title>
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
    <link rel="stylesheet" href="<?= admin_asset_url('css/auth.css'); ?>">
</head>
<body class="auth-body">
    <?= $content ?? ''; ?>
    <script src="<?= admin_asset_url('js/admin.js'); ?>"></script>
</body>
</html>