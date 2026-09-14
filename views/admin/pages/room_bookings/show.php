<div class="page-head">
    <h1><?= e($booking['booking_code']); ?></h1>
    <p><?= e($booking['activity_name']); ?></p>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Informasi Peminjaman</h3>
        <table class="detail-table">
            <tr><th>Ruangan</th><td><?= e($booking['room_name'] ?? '-'); ?> (kap. <?= (int) ($booking['room_capacity'] ?? 0); ?>)</td></tr>
            <tr><th>Peminjam</th><td><?= e($booking['borrower_name']); ?> (<?= e($booking['borrower_type']); ?>)</td></tr>
            <tr><th>Unit</th><td><?= e($booking['unit_name'] ?? '-'); ?></td></tr>
            <tr><th>Waktu</th><td><?= e(format_tanggal_waktu($booking['start_at'])); ?> - <?= e(date('H:i', strtotime($booking['end_at']))); ?></td></tr>
            <tr><th>Peserta</th><td><?= (int) $booking['participant_count']; ?> orang</td></tr>
            <tr><th>Fasilitas Diminta</th><td><?= e($booking['facilities_needed'] ?? '-'); ?></td></tr>
            <tr><th>Status</th><td><span class="badge <?= e(status_badge_class($booking['status'])); ?>"><?= e($booking['status']); ?></span></td></tr>
            <tr><th>Persetujuan</th><td><?= e($booking['approval_status']); ?></td></tr>
        </table>
    </div>

    <div class="card">
        <h3 class="card-title">Aksi Penanganan</h3>

        <?php if ($booking['status'] === 'Menunggu Verifikasi'): ?>
            <form method="POST" action="<?= admin_url('/peminjaman-ruangan/' . $booking['id'] . '/verifikasi'); ?>">
                <?= csrf_field(); ?>
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Verifikasi</button></div>
            </form>
        <?php endif; ?>

        <?php if (in_array($booking['status'], ['Menunggu Verifikasi', 'Diverifikasi'], true) && $booking['approval_status'] === 'Menunggu'): ?>
            <form method="POST" action="<?= admin_url('/peminjaman-ruangan/' . $booking['id'] . '/persetujuan'); ?>" style="margin-top:.8rem;">
                <?= csrf_field(); ?>
                <input type="hidden" name="decision" value="Disetujui">
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Setujui</button></div>
            </form>
            <form method="POST" action="<?= admin_url('/peminjaman-ruangan/' . $booking['id'] . '/persetujuan'); ?>" style="margin-top:.5rem;">
                <?= csrf_field(); ?>
                <input type="hidden" name="decision" value="Ditolak">
                <div class="form-actions"><button class="btn btn-danger btn-sm" type="submit">Tolak</button></div>
            </form>
        <?php endif; ?>

        <?php if (in_array($booking['status'], ['Disetujui', 'Ditolak'], true)): ?>
            <p class="empty-note">Keputusan telah ditetapkan.<?= $booking['status'] === 'Disetujui' ? ' Log utilitas ruangan telah dicatat.' : ''; ?></p>
        <?php endif; ?>
    </div>
</div>