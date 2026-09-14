<?php
$hour = (int) date('G');
$greet = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
?>
<div class="welcome">
    <div class="welcome-orb o1"></div>
    <div class="welcome-orb o2"></div>
    <div>
        <h1><?= e($greet); ?>, <?= e($user['full_name'] ?? ''); ?></h1>
        <p><?= e(format_tanggal_panjang(date('Y-m-d'))); ?> &mdash; berikut ringkasan operasional sarpras hari ini.</p>
    </div>
    <div class="welcome-actions">
        <a class="btn btn-light" href="<?= admin_url('/aset/tambah'); ?>">+ Aset</a>
        <a class="btn btn-light" href="<?= admin_url('/surat/keluar/tambah'); ?>">+ Surat</a>
        <a class="btn btn-light" href="<?= admin_url('/backup/unduh'); ?>">Backup</a>
    </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <?php
    $cards = [
        ['Aset', $stats['assets'], 'M21 8l-9-5-9 5v8l9 5 9-5V8z', '0,24 15,20 30,22 45,14 60,17 75,9 100,12'],
        ['Ruangan', $stats['rooms'], 'M3 21h18|M5 21V7l7-4 7 4v14', '0,20 15,22 30,16 45,18 60,10 75,14 100,8'],
        ['Tiket Menunggu', $stats['tickets'], 'M12 9v4|M12 17h.01|M10.3 3.8 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0z', '0,18 15,14 30,19 45,11 60,16 75,7 100,10'],
        ['Peminjaman', $stats['borrows'], 'M3 4h18v18H3z|M3 10h18', '0,22 15,18 30,20 45,12 60,15 75,8 100,11'],
        ['Pengadaan', $stats['procurements'], 'M3 3v18h18|M8 17v-6|M13 17V7', '0,20 15,16 30,18 45,10 60,14 75,6 100,9'],
        ['Permintaan', $stats['item_requests'], 'M12 20h9|M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z', '0,21 15,17 30,19 45,13 60,16 75,9 100,12'],
    ];
    foreach ($cards as $c): ?>
        <div class="stat-card">
            <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php foreach (explode('|', $c[2]) as $d): ?><path d="<?= e($d); ?>"/><?php endforeach; ?></svg></span>
            <span style="flex:1"><span class="stat-value"><?= (int) $c[1]; ?></span><span class="stat-label"><?= e($c[0]); ?></span></span>
            <svg class="spark" viewBox="0 0 100 30" style="width:86px;height:28px;opacity:.9"><polyline points="<?= e($c[3]); ?>" fill="none" stroke="url(#gradSpark)" stroke-width="2.4" stroke-linecap="round"/></svg>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Laporan Kerusakan per Status</h3>
        <?php if ($tickets_by_status === []): ?>
            <p class="empty-note">Belum ada data tiket.</p>
        <?php else: ?>
            <?php $max = max(1, (int) max(array_column($tickets_by_status, 'total'))); ?>
            <?php foreach ($tickets_by_status as $row): ?>
                <div class="bar-row">
                    <span class="bar-label"><?= e($row['status']); ?></span>
                    <div class="bar-track"><div class="bar-fill" data-width="<?= (int) round($row['total'] / $max * 100); ?>%" style="width:<?= (int) round($row['total'] / $max * 100); ?>%"></div></div>
                    <span class="bar-value"><?= (int) $row['total']; ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <h3 class="card-title" style="margin-top:1.4rem;">Aset per Kategori</h3>
        <?php if ($assets_by_category === []): ?>
            <p class="empty-note">Belum ada data aset.</p>
        <?php else: ?>
            <?php $maxC = max(1, (int) max(array_column($assets_by_category, 'total'))); ?>
            <?php foreach ($assets_by_category as $row): ?>
                <div class="bar-row">
                    <span class="bar-label"><?= e($row['name']); ?></span>
                    <div class="bar-track"><div class="bar-fill" data-width="<?= (int) round($row['total'] / $maxC * 100); ?>%" style="width:<?= (int) round($row['total'] / $maxC * 100); ?>%"></div></div>
                    <span class="bar-value"><?= (int) $row['total']; ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3 class="card-title">Kalender Pemeliharaan</h3>
        <?php require base_path('views/admin/components/calendar.php'); ?>

        <h4 class="sub-title">Jadwal Mendatang</h4>
        <?php if ($maintenance_upcoming === []): ?>
            <p class="empty-note">Tidak ada jadwal mendatang.</p>
        <?php else: ?>
            <ul class="list-plain">
                <?php foreach ($maintenance_upcoming as $row): ?>
                    <li><span><?= e($row['title']); ?></span><span><?= e(format_tanggal($row['schedule_date'])); ?></span></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($maintenance_overdue > 0): ?>
            <div class="overdue-note">Terdapat <?= (int) $maintenance_overdue; ?> jadwal terlambat yang belum diselesaikan.</div>
        <?php endif; ?>

        <h4 class="sub-title">Aktivitas Terbaru</h4>
        <?php if ($recent_activity === []): ?>
            <p class="empty-note">Belum ada aktivitas.</p>
        <?php else: ?>
            <ul class="activity-list">
                <?php foreach ($recent_activity as $row): ?>
                    <li class="activity-item">
                        <span class="activity-action"><?= e($row['action']); ?> &middot; <?= e($row['module']); ?></span>
                        <span class="activity-time"><?= e(format_tanggal_waktu($row['created_at'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>