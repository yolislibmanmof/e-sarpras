<div class="page-head">
    <h1>Surat &amp; Disposisi</h1>
    <p>Administrasi surat masuk dan surat keluar Bagian Sarpras.</p>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/surat'); ?>" class="toolbar-form">
            <input type="text" name="q" value="<?= e($q); ?>" placeholder="Cari nomor, perihal, pengirim...">
            <select name="direction">
                <option value="">Semua Arah</option>
                <option value="incoming" <?= $direction === 'incoming' ? 'selected' : ''; ?>>Surat Masuk</option>
                <option value="outgoing" <?= $direction === 'outgoing' ? 'selected' : ''; ?>>Surat Keluar</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
        <div class="row-actions">
            <?php if (can('letter.create')): ?>
                <a class="btn btn-secondary btn-sm" href="<?= admin_url('/surat/masuk/tambah'); ?>">+ Surat Masuk</a>
                <a class="btn btn-primary btn-sm" href="<?= admin_url('/surat/keluar/tambah'); ?>">+ Surat Keluar</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Nomor</th><th>Arah</th><th>Perihal</th><th>Pihak</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="7" class="empty-note">Belum ada surat.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= e($row['letter_number'] ?? '-'); ?></td>
                            <td>
                                <span class="badge <?= $row['direction'] === 'incoming' ? 'badge-info' : 'badge-success'; ?>">
                                    <?= $row['direction'] === 'incoming' ? 'Masuk' : 'Keluar'; ?>
                                </span>
                            </td>
                            <td><?= e(str_limit($row['subject'] ?? '-', 45)); ?></td>
                            <td><?= e($row['direction'] === 'incoming' ? ($row['sender_name'] ?? '-') : ($row['recipient_name'] ?? '-')); ?></td>
                            <td><?= e(format_tanggal($row['letter_date'])); ?></td>
                            <td><span class="badge <?= e(status_badge_class($row['status'])); ?>"><?= e($row['status']); ?></span></td>
                            <td><a class="btn btn-secondary btn-sm" href="<?= admin_url('/surat/' . $row['id']); ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require base_path('views/admin/components/pagination.php'); ?>
</div>