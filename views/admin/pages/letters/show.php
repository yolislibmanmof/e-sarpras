<div class="page-head">
    <h1><?= e($letter['subject'] ?? '-'); ?></h1>
    <p><?= e($letter['letter_number'] ?? 'Tanpa nomor'); ?> &middot; <?= $letter['direction'] === 'incoming' ? 'Surat Masuk' : 'Surat Keluar'; ?></p>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Informasi Surat</h3>
        <table class="detail-table">
            <tr><th>Status</th><td><span class="badge <?= e(status_badge_class($letter['status'])); ?>"><?= e($letter['status']); ?></span></td></tr>
            <tr><th>Jenis</th><td><?= e($letter['letter_type']); ?></td></tr>
            <tr><th>Tanggal</th><td><?= e(format_tanggal($letter['letter_date'])); ?></td></tr>
            <?php if ($letter['direction'] === 'incoming'): ?>
                <tr><th>Pengirim</th><td><?= e($letter['sender_name']); ?> (<?= e($letter['sender_unit'] ?? '-'); ?>)</td></tr>
            <?php else: ?>
                <tr><th>Penerima</th><td><?= e($letter['recipient_name']); ?> (<?= e($letter['recipient_unit'] ?? '-'); ?>)</td></tr>
            <?php endif; ?>
        </table>

        <?php if ($letter['content'] !== null): ?>
            <h4 class="sub-title">Isi / Ringkasan</h4>
            <p class="detail-desc"><?= e($letter['content']); ?></p>
        <?php endif; ?>

        <h4 class="sub-title">Lampiran</h4>
        <?php if ($attachments === []): ?>
            <p class="empty-note">Tidak ada lampiran.</p>
        <?php else: ?>
            <div class="attachment-grid">
                <?php foreach ($attachments as $attachment): ?>
                    <a class="btn btn-secondary btn-sm" target="_blank" href="<?= admin_url('/surat/file/' . $attachment['id']); ?>">Lihat Lampiran #<?= (int) $attachment['id']; ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <?php if ($letter['direction'] === 'outgoing'): ?>
                <a class="btn btn-primary btn-sm" target="_blank" href="<?= admin_url('/surat/' . $letter['id'] . '/cetak'); ?>">Cetak Surat</a>
            <?php endif; ?>
            <?php if ($letter['status'] !== 'Diarsipkan' && can('letter.archive')): ?>
                <form method="POST" action="<?= admin_url('/surat/' . $letter['id'] . '/arsip'); ?>">
                    <?= csrf_field(); ?>
                    <button class="btn btn-secondary btn-sm" type="submit">Arsipkan</button>
                </form>
            <?php endif; ?>
            <a class="btn btn-secondary btn-sm" href="<?= admin_url('/surat'); ?>">Kembali</a>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Disposisi</h3>

        <?php if ($dispositions === []): ?>
            <p class="empty-note">Belum ada disposisi.</p>
        <?php else: ?>
            <ul class="list-plain">
                <?php foreach ($dispositions as $disposition): ?>
                    <li>
                        <span>
                            <strong><?= e($disposition['from_name'] ?? '-'); ?> &rarr; <?= e($disposition['to_name'] ?? '-'); ?></strong><br>
                            <?= e($disposition['content'] ?? ''); ?><br>
                            <?php if ($disposition['instruction'] !== null): ?>
                                <em>Disposisi: <?= e($disposition['instruction']); ?></em><br>
                            <?php endif; ?>
                            <span class="badge <?= e(status_badge_class($disposition['status'])); ?>"><?= e($disposition['status']); ?></span>
                        </span>
                        <span><?= e(format_tanggal($disposition['disposition_date'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($letter['direction'] === 'incoming' && $letter['status'] === 'Masuk' && can('disposition.create')): ?>
            <h4 class="sub-title">Teruskan ke Pimpinan</h4>
            <form method="POST" action="<?= admin_url('/surat/' . $letter['id'] . '/disposisi/kirim'); ?>">
                <?= csrf_field(); ?>
                <label for="to_user_id">Tujuan Disposisi *</label>
                <select id="to_user_id" name="to_user_id" required>
                    <option value="">Pilih atasan</option>
                    <?php foreach ($superiors as $superior): ?>
                        <option value="<?= (int) $superior['id']; ?>"><?= e($superior['full_name']); ?> (<?= e($superior['role_code']); ?>)</option>
                    <?php endforeach; ?>
                </select>
                <label for="disp_content">Catatan</label>
                <textarea id="disp_content" name="content" rows="2">Mohon tinjauan dan disposisi.</textarea>
                <div class="form-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Kirim ke Pimpinan</button>
                </div>
            </form>
        <?php endif; ?>

        <?php foreach ($dispositions as $disposition): ?>
            <?php if ($disposition['status'] === 'Menunggu' && $isSuperior): ?>
                <h4 class="sub-title">Isi Disposisi (Pimpinan)</h4>
                <form method="POST" action="<?= admin_url('/disposisi/' . $disposition['id'] . '/isi'); ?>">
                    <?= csrf_field(); ?>
                    <label for="instruction">Instruksi Disposisi *</label>
                    <textarea id="instruction" name="instruction" rows="2" required></textarea>
                    <div class="form-actions">
                        <button class="btn btn-primary btn-sm" type="submit">Simpan Disposisi</button>
                    </div>
                </form>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($letter['status'] === 'Didisposisi' && can('letter.update')): ?>
            <form method="POST" action="<?= admin_url('/surat/' . $letter['id'] . '/tindak-lanjut'); ?>" style="margin-top:1rem;">
                <?= csrf_field(); ?>
                <div class="form-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Tindak Lanjuti</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>