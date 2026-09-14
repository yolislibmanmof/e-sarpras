<?php $queryBase = http_build_query(array_filter(['type' => $type, 'from' => $from, 'to' => $to], static fn ($v) => $v !== '')); ?>

<div class="page-head">
    <h1>Laporan &amp; Ekspor</h1>
    <p>Penyediaan data operasional dan borang akreditasi dalam satu klik.</p>
</div>

<div class="table-card" style="margin-bottom:1.4rem;">
    <div class="table-toolbar">
        <form method="GET" action="<?= admin_url('/laporan'); ?>" class="toolbar-form">
            <select name="type">
                <?php foreach ($types as $key => $label): ?>
                    <option value="<?= e($key); ?>" <?= $type === $key ? 'selected' : ''; ?>><?= e($label); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="from" value="<?= e($from); ?>">
            <input type="date" name="to" value="<?= e($to); ?>">
            <button type="submit" class="btn btn-secondary btn-sm">Tampilkan</button>
        </form>
        <div class="row-actions">
            <a class="btn btn-secondary btn-sm" target="_blank" href="<?= admin_url('/laporan/cetak?' . $queryBase); ?>">Cetak / PDF</a>
            <?php if (can('report.export')): ?>
                <a class="btn btn-primary btn-sm" href="<?= admin_url('/laporan/ekspor?' . $queryBase . '&format=csv'); ?>">CSV</a>
                <a class="btn btn-primary btn-sm" href="<?= admin_url('/laporan/ekspor?' . $queryBase . '&format=xls'); ?>">Excel</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <?php foreach ($report['headers'] as $header): ?>
                        <th><?= e($header); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($report['rows'] === []): ?>
                    <tr><td colspan="<?= count($report['headers']); ?>" class="empty-note">Tidak ada data pada rentang ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($report['rows'] as $row): ?>
                        <tr>
                            <?php foreach ($report['headers'] as $key => $header): ?>
                                <?php $keys = array_keys($row); $value = $row[$keys[$key]] ?? null; ?>
                                <td>
                                    <?php if ($value === null): ?>
                                        -
                                    <?php elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', (string) $value)): ?>
                                        <?= e(format_tanggal_waktu((string) $value)); ?>
                                    <?php elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value)): ?>
                                        <?= e(format_tanggal((string) $value)); ?>
                                    <?php elseif (is_numeric($value) && $header === 'Nilai Perolehan'): ?>
                                        <?= e(format_rupiah($value)); ?>
                                    <?php else: ?>
                                        <?= e($value); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>