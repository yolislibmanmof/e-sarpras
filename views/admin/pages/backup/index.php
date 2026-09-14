<div class="page-head">
    <h1>Backup Database</h1>
    <p>Unduh salinan lengkap database untuk keperluan backup dan pemulihan.</p>
</div>

<div class="card">
    <h3 class="card-title">Informasi Backup</h3>
    <p>Backup akan menghasilkan file SQL dump yang berisi seluruh struktur tabel dan data dari database <strong><?= e(config('database.database')); ?></strong>.</p>
    <p>Gunakan file ini untuk:</p>
    <ul style="margin-left:1.2rem; margin-top:.6rem;">
        <li>Backup berkala sebelum melakukan perubahan besar</li>
        <li>Migrasi database ke server lain</li>
        <li>Pemulihan data jika terjadi kerusakan</li>
    </ul>

    <div class="form-actions" style="margin-top:1.4rem;">
        <a class="btn btn-primary" href="<?= admin_url('/backup/unduh'); ?>">Unduh Backup Sekarang</a>
    </div>
</div>