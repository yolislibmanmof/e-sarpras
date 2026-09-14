<?php $open = !in_array($ticket['status'], ['Selesai', 'Ditolak'], true); ?>

<div class="page-head">
    <h1><?= e($ticket['title']); ?></h1>
    <p><?= e($ticket['ticket_code']); ?> &middot; Dilaporkan oleh <?= e($ticket['reporter_name']); ?> (<?= e($ticket['reporter_type']); ?>)</p>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Informasi Laporan</h3>
        <table class="detail-table">
            <tr><th>Status</th><td><span class="badge <?= e(status_badge_class($ticket['status'])); ?>"><?= e($ticket['status']); ?></span></td></tr>
            <tr><th>Urgensi</th><td><?= e($ticket['priority']); ?></td></tr>
            <tr><th>Kategori</th><td><?= e($ticket['category']); ?></td></tr>
            <tr><th>Lokasi</th><td><?= e(trim(($ticket['building_name'] ?? '-') . ' / ' . ($ticket['room_name'] ?? '-'))); ?></td></tr>
            <tr><th>Aset Terkait</th><td><?= e($ticket['asset_name'] ?? '-'); ?></td></tr>
            <tr><th>Kontak Pelapor</th><td><?= e($ticket['reporter_contact']); ?></td></tr>
            <tr><th>Tanggal Kejadian</th><td><?= e(format_tanggal($ticket['incident_date'])); ?></td></tr>
            <tr><th>Estimasi Selesai</th><td><?= e(format_tanggal($ticket['estimated_completion'])); ?></td></tr>
            <tr><th>Petugas</th><td><?= e($ticket['technician_name'] ?? $ticket['vendor_name'] ?? '-'); ?></td></tr>
        </table>
        <p class="sub-title">Deskripsi</p>
        <p class="detail-desc"><?= e($ticket['description']); ?></p>

        <h4 class="sub-title">Lampiran</h4>
        <?php if ($attachments === []): ?>
            <p class="empty-note">Tidak ada lampiran.</p>
        <?php else: ?>
            <div class="attachment-grid">
                <?php foreach ($attachments as $attachment): ?>
                    <a class="btn btn-secondary btn-sm" target="_blank" href="<?= admin_url('/tiket/file/' . $attachment['id']); ?>">Lihat Lampiran #<?= (int) $attachment['id']; ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3 class="card-title">Aksi Penanganan</h3>

        <?php if ($ticket['status'] === 'Menunggu Verifikasi'): ?>
            <form method="POST" action="<?= admin_url('/tiket/' . $ticket['id'] . '/verifikasi'); ?>">
                <?= csrf_field(); ?>
                <label for="verify_note">Catatan Verifikasi</label>
                <input id="verify_note" name="note" placeholder="Opsional">
                <div class="form-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Verifikasi Tiket</button>
                </div>
            </form>

            <form method="POST" action="<?= admin_url('/tiket/' . $ticket['id'] . '/tolak'); ?>" style="margin-top:1rem;">
                <?= csrf_field(); ?>
                <label for="reject_note">Alasan Penolakan *</label>
                <textarea id="reject_note" name="note" rows="2" required></textarea>
                <div class="form-actions">
                    <button class="btn btn-danger btn-sm" type="submit">Tolak Tiket</button>
                </div>
            </form>
        <?php endif; ?>

        <?php if ($open): ?>
            <form method="POST" action="<?= admin_url('/tiket/' . $ticket['id'] . '/tugas'); ?>" style="margin-top:1rem;">
                <?= csrf_field(); ?>
                <h4 class="sub-title">Penugasan</h4>
                <div class="form-grid">
                    <div>
                        <label for="assignee_type">Jenis Petugas</label>
                        <select id="assignee_type" name="assignee_type" required>
                            <option value="technician">Teknisi Internal</option>
                            <option value="vendor">Vendor Eksternal</option>
                        </select>
                    </div>
                    <div>
                        <label for="assignee_id">Pilih Petugas</label>
                        <select id="assignee_id" name="assignee_id" required>
                            <optgroup label="Teknisi">
                                <?php foreach ($technicians as $tech): ?>
                                    <option value="t<?= (int) $tech['id']; ?>" disabled hidden></option>
                                    <option value="<?= (int) $tech['id']; ?>"><?= e($tech['name']); ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label="Vendor">
                                <?php foreach ($vendors as $vendor): ?>
                                    <option value="<?= (int) $vendor['id']; ?>"><?= e($vendor['name']); ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label for="assign_priority">Urgensi</label>
                        <select id="assign_priority" name="priority">
                            <?php foreach (['Normal', 'Mendesak', 'Darurat'] as $option): ?>
                                <option value="<?= e($option); ?>" <?= $ticket['priority'] === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="estimated_completion">Estimasi Selesai</label>
                        <input id="estimated_completion" name="estimated_completion" type="date" value="<?= e($ticket['estimated_completion'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Tugaskan</button>
                </div>
            </form>

            <form method="POST" action="<?= admin_url('/tiket/' . $ticket['id'] . '/status'); ?>" style="margin-top:1rem;">
                <?= csrf_field(); ?>
                <h4 class="sub-title">Perbarui Status</h4>
                <label for="new_status">Status Baru</label>
                <select id="new_status" name="new_status" required>
                    <?php foreach (['Sedang Diperbaiki', 'Menunggu Sparepart', 'Selesai'] as $option): ?>
                        <option value="<?= e($option); ?>"><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="status_note">Catatan</label>
                <textarea id="status_note" name="note" rows="2"></textarea>
                <div class="form-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Simpan Status</button>
                </div>
            </form>
        <?php else: ?>
            <p class="empty-note">Tiket telah <?= e(strtolower($ticket['status'])); ?>. Tidak ada aksi lanjutan.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <h3 class="card-title">Riwayat Status</h3>
    <ul class="list-plain">
        <?php foreach ($histories as $history): ?>
            <li>
                <span><strong><?= e($history['new_status']); ?></strong> &mdash; <?= e($history['note'] ?? ''); ?></span>
                <span><?= e(format_tanggal_waktu($history['created_at'])); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>