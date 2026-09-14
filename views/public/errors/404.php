<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="<?= asset_url('css/main.css'); ?>">
</head>
<body class="error-page">
    <div class="error-box">
        <h1>404</h1>
        <p>Halaman yang Anda cari tidak ditemukan.</p>
        <a class="btn btn-primary" href="<?= base_url('/'); ?>">Kembali ke Beranda</a>
    </div>
</body>
</html>