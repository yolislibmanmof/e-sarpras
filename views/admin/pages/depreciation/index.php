<div class="page-head">
    <h1>Penyusutan Aset</h1>
    <p>Metode garis lurus (straight-line): nilai buku = nilai perolehan &minus; akumulasi penyusutan.</p>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card"><span><span class="stat-value"><?= e(format_rupiah($totals['value'])); ?></span><span class="stat-label">Total Nilai Perolehan</span></span></div>
    <div class="stat-card"><span><span class="stat-value"><?= e(format_rupiah($totals['accum'])); ?></span><span class="stat-label">Total Akumulasi Penyusutan</span></span></div>
    <div class="stat-card"><span><span class="stat-value"><?= e(format_rupiah($totals['book'])); ?></span><span class="stat-label">Total Nilai Buku</span></span></div>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <span class="empty-note">Atur umur ekonomis &amp; nilai sisa per aset pada kolom terakhir.</span>
        <div class="row-actions">
            <a class="btn btn-primary btn-sm" href="<?= admin_url('/aset/penyusutan/ekspor'); ?>">Ekspor CSV</a>
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Kode / Nama</th><th>Nilai Perolehan</th><th>Umur (th)</th><th>Penyusutan/Th</th><th>Akumulasi</th><th>Nilai Buku</th><th>Parameter</th></tr>
            </thead>
            <tbody>
                <?php if ($items === []): ?>
                    <tr><td colspan="7" class="empty-note">Belum ada aset bernilai untuk disusutkan.</td></tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><strong><?= e($item['code']); ?></strong><br><small style="color:var(--muted);"><?= e($item['name']); ?> &middot; <?= e($item['category_name'] ?? '-'); ?></small></td>
                            <td><?= e(format_rupiah($item['value'])); ?></td>
                            <td><?= $item['life'] > 0 ? (int) $item['life'] : '-'; ?></td>
                            <td><?= e(format_rupiah($item['annual'])); ?></td>
                            <td><?= e(format_rupiah($item['accum'])); ?></td>
                            <td><strong><?= e(format_rupiah($item['book'])); ?></strong></td>
                            <td>
                                <form method="POST" action="<?= admin_url('/aset/' . $item['id'] . '/umur'); ?>" class="toolbar-form">
                                    <?= csrf_field(); ?>
                                    <input type="number" name="useful_life_years" min="1" value="<?= (int) ($item['life'] ?: 5); ?>" style="width:70px;" title="Umur ekonomis (tahun)">
                                    <input type="number" name="salvage_value" min="0" value="<?= (float) $item['salvage']; ?>" style="width:110px;" title="Nilai sisa">
                                    <button class="btn btn-secondary btn-sm" type="submit">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>