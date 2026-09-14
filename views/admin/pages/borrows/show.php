<div class="page-head">
    <h1><?= e($request['borrow_code']); ?></h1>
    <p><?= e($request['borrower_name']); ?> (<?= e($request['borrower_type']); ?>) &middot; <?= e($request['unit_name'] ?? '-'); ?></p>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Informasi Peminjaman</h3>
        <table class="detail-table">
            <tr><th>Status</th><td><span class="badge <?= e(status_badge_class($request['status'])); ?>"><?= e($request['status']); ?></span></td></tr>
            <tr><th>Persetujuan</th><td><?= e($request['approval_status']); ?></td></tr>
            <tr><th>Kontak</th><td><?= e($request['phone']); ?> <?= e($request['email'] ?? ''); ?></td></tr>
            <tr><th>Keperluan</th><td><?= e($request['purpose']); ?></td></tr>
            <tr><th>Kegiatan</th><td><?= e($request['event_name'] ?? '-'); ?></td></tr>
            <tr><th>Lokasi Penggunaan</th><td><?= e($request['location'] ?? '-'); ?></td></tr>
            <tr><th>Tanggal Pinjam</th><td><?= e(format_tanggal($request['borrow_date'])); ?></td></tr>
            <tr><th>Rencana Kembali</th><td><?= e(format_tanggal($request['expected_return_date'])); ?></td></tr>
            <tr><th>Realisasi Kembali</th><td><?= e(format_tanggal_waktu($request['actual_return_date'])); ?></td></tr>
        </table>

        <h4 class="sub-title">Daftar Barang</h4>
        <table class="data-table">
            <thead><tr><th>Barang</th><th>Jumlah</th><th>Kondisi Awal</th><th>Kondisi Akhir</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['asset_code'] ?? '-'); ?> - <?= e($item['item_name']); ?></td>
                        <td><?= (int) $item['quantity']; ?></td>
                        <td><?= e($item['condition_before'] ?? '-'); ?></td>
                        <td><?= e($item['condition_after'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 class="card-title">Aksi Penanganan</h3>

        <?php if ($request['status'] === 'Menunggu Verifikasi'): ?>
            <form method="POST" action="<?= admin_url('/peminjaman-barang/' . $request['id'] . '/verifikasi'); ?>">
                <?= csrf_field(); ?>
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Verifikasi</button></div>
            </form>
        <?php endif; ?>

        <?php if (in_array($request['status'], ['Menunggu Verifikasi', 'Diverifikasi'], true) && $request['approval_status'] === 'Menunggu'): ?>
            <form method="POST" action="<?= admin_url('/peminjaman-barang/' . $request['id'] . '/persetujuan'); ?>" style="margin-top:.8rem;">
                <?= csrf_field(); ?>
                <input type="hidden" name="decision" value="Disetujui">
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Setujui</button></div>
            </form>
            <form method="POST" action="<?= admin_url('/peminjaman-barang/' . $request['id'] . '/persetujuan'); ?>" style="margin-top:.5rem;">
                <?= csrf_field(); ?>
                <input type="hidden" name="decision" value="Ditolak">
                <div class="form-actions"><button class="btn btn-danger btn-sm" type="submit">Tolak</button></div>
            </form>
        <?php endif; ?>

        <?php if ($request['status'] === 'Diverifikasi' && $request['approval_status'] === 'Disetujui'): ?>
            <form method="POST" action="<?= admin_url('/peminjaman-barang/' . $request['id'] . '/serah'); ?>" style="margin-top:.8rem;">
                <?= csrf_field(); ?>
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Serahkan Barang</button></div>
            </form>
        <?php endif; ?>

        <?php if ($request['status'] === 'Dipinjam'): ?>
            <form method="POST" action="<?= admin_url('/peminjaman-barang/' . $request['id'] . '/kembali'); ?>" style="margin-top:.8rem;">
                <?= csrf_field(); ?>
                <label for="condition_after">Kondisi Saat Kembali</label>
                <select id="condition_after" name="condition_after">
                    <?php foreach (['Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'] as $option): ?>
                        <option value="<?= e($option); ?>"><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="notes">Catatan Pengembalian</label>
                <textarea id="notes" name="notes" rows="2"></textarea>
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Proses Pengembalian</button></div>
            </form>
        <?php endif; ?>

        <?php if (in_array($request['status'], ['Selesai', 'Ditolak'], true)): ?>
            <p class="empty-note">Proses peminjaman telah berakhir.</p>
        <?php endif; ?>
    </div>
</div>