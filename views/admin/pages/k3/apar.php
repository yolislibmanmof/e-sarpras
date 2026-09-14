<?php $today = date('Y-m-d'); ?>
<?php $soon = date('Y-m-d', strtotime('+30 days')); ?>

<div class="page-head">
    <h1>Manajemen APAR</h1>
    <p>Pelacakan kondisi dan tanggal kedaluwarsa alat pemadam api ringan.</p>
</div>

<div class="grid-2">
    <div class="table-card">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Kode</th><th>Lokasi</th><th>Kedaluwarsa</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($rows === []): ?>
                        <tr><td colspan="5" class="empty-note">Belum ada APAR.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= e($row['code']); ?></td>
                                <td><?= e($row['location'] ?? $row['building_name'] ?? '-'); ?></td>
                                <td>
                                    <?php if ($row['expiry_date'] !== null && $row['expiry_date'] < $today): ?>
                                        <span class="badge badge-danger">Kedaluwarsa <?= e(format_tanggal($row['expiry_date'])); ?></span>
                                    <?php elseif ($row['expiry_date'] !== null && $row['expiry_date'] <= $soon): ?>
                                        <span class="badge badge-warning">Segera <?= e(format_tanggal($row['expiry_date'])); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?= e(format_tanggal($row['expiry_date'])); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($row['status']); ?></td>
                                <td>
                                    <?php if (can('k3.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/k3/apar/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus APAR ini?">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Tambah APAR</h3>
        <form method="POST" action="<?= admin_url('/k3/apar'); ?>">
            <?= csrf_field(); ?>
            <div class="form-grid">
                <div><label for="code">Kode *</label><input id="code" name="code" required></div>
                <div><label for="apar_type">Jenis</label><input id="apar_type" name="apar_type" placeholder="Contoh: Powder, CO2"></div>
                <div><label for="capacity">Kapasitas</label><input id="capacity" name="capacity" placeholder="Contoh: 3 kg"></div>
                <div><label for="location">Titik Lokasi</label><input id="location" name="location"></div>
                <div><label for="expiry_date">Tanggal Kedaluwarsa</label><input id="expiry_date" name="expiry_date" type="date"></div>
                <div><label for="next_service_date">Servis Berikutnya</label><input id="next_service_date" name="next_service_date" type="date"></div>
            </div>
            <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan</button></div>
        </form>
    </div>
</div>