<div class="page-head">
    <h1><?= e($request['request_code']); ?></h1>
    <p><?= e($request['requester_name']); ?> (<?= e($request['requester_type']); ?>) &middot; <?= e($request['unit_name']); ?></p>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Informasi Permintaan</h3>
        <table class="detail-table">
            <tr><th>Jenis</th><td><?= e($request['request_type']); ?></td></tr>
            <tr><th>Keperluan</th><td><?= e($request['purpose']); ?></td></tr>
            <tr><th>Tanggal Dibutuhkan</th><td><?= e(format_tanggal($request['needed_date'])); ?></td></tr>
            <tr><th>Status</th><td><span class="badge <?= e(status_badge_class($request['status'])); ?>"><?= e($request['status']); ?></span></td></tr>
            <tr><th>Persetujuan</th><td><?= e($request['approval_status']); ?></td></tr>
        </table>

        <h4 class="sub-title">Daftar Barang</h4>
        <table class="data-table">
            <thead><tr><th>Barang</th><th>Jumlah</th><th>Stok Tersedia</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['stock_code'] ?? '-'); ?> - <?= e($item['item_name']); ?></td>
                        <td><?= (float) $item['quantity']; ?></td>
                        <td><?= $item['stock_id'] !== null ? (float) $item['stock_available'] : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 class="card-title">Aksi Penanganan</h3>

        <?php if ($request['status'] === 'Menunggu Verifikasi'): ?>
            <form method="POST" action="<?= admin_url('/permintaan-barang/' . $request['id'] . '/verifikasi'); ?>">
                <?= csrf_field(); ?>
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Verifikasi</button></div>
            </form>
        <?php endif; ?>

        <?php if (in_array($request['status'], ['Menunggu Verifikasi', 'Diverifikasi'], true) && $request['approval_status'] === 'Menunggu'): ?>
            <form method="POST" action="<?= admin_url('/permintaan-barang/' . $request['id'] . '/persetujuan'); ?>" style="margin-top:.8rem;">
                <?= csrf_field(); ?>
                <input type="hidden" name="decision" value="Disetujui">
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Setujui</button></div>
            </form>
            <form method="POST" action="<?= admin_url('/permintaan-barang/' . $request['id'] . '/persetujuan'); ?>" style="margin-top:.5rem;">
                <?= csrf_field(); ?>
                <input type="hidden" name="decision" value="Ditolak">
                <div class="form-actions"><button class="btn btn-danger btn-sm" type="submit">Tolak</button></div>
            </form>
        <?php endif; ?>

        <?php if ($request['status'] === 'Diverifikasi' && $request['approval_status'] === 'Disetujui'): ?>
            <form method="POST" action="<?= admin_url('/permintaan-barang/' . $request['id'] . '/serahkan'); ?>" style="margin-top:.8rem;">
                <?= csrf_field(); ?>
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Serahkan Barang</button></div>
            </form>
        <?php endif; ?>

        <?php if (in_array($request['status'], ['Diserahkan', 'Ditolak'], true)): ?>
            <p class="empty-note">Proses permintaan telah berakhir.</p>
        <?php endif; ?>
    </div>
</div>