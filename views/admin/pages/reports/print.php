<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($report['title']); ?></title>
    <style>
        body { font-family: "Times New Roman", serif; color: #000; margin: 0; }
        .sheet { width: 277mm; margin: 0 auto; padding: 15mm; }
        .kop { display: flex; align-items: center; gap: 12px; border-bottom: 3px double #000; padding-bottom: 8px; }
        .kop-logo { width: 60px; height: 60px; border: 2px solid #000; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .l1 { font-size: 13pt; font-weight: bold; }
        .kop-text .l2 { font-size: 11pt; font-weight: bold; }
        .kop-text .l3 { font-size: 9pt; }
        h2 { font-size: 12pt; margin: 14px 0 4px; }
        .period { font-size: 9pt; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        th, td { border: 1px solid #000; padding: 4px 6px; text-align: left; vertical-align: top; }
        th { background: #eee; }
        .sign { margin-top: 24px; display: flex; justify-content: flex-end; }
        .sign-box { text-align: center; font-size: 10pt; }
        .sign-space { height: 60px; }
        .no-print { padding: 10px; text-align: center; }
        .no-print button { padding: 8px 18px; cursor: pointer; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print"><button type="button" onclick="window.print()">Cetak / Simpan PDF</button></div>

    <div class="sheet">
        <div class="kop">
            <div class="kop-logo">LOGO</div>
            <div class="kop-text">
                <div class="l1"><?= e(setting_value('campus_name', 'Kampus')); ?></div>
                <div class="l2">BAGIAN SARANA DAN PRASARANA</div>
                <div class="l3"><?= e(setting_value('campus_address', '')); ?></div>
            </div>
        </div>

        <h2><?= e($report['title']); ?></h2>
        <p class="period">
            Periode: <?= $from !== '' ? e(format_tanggal($from)) : 'Awal'; ?> s.d. <?= $to !== '' ? e(format_tanggal($to)) : 'Sekarang'; ?>
            &middot; Dicetak: <?= e(format_tanggal_waktu(date('Y-m-d H:i:s'))); ?>
        </p>

        <table>
            <thead>
                <tr>
                    <?php foreach ($report['headers'] as $header): ?>
                        <th><?= e($header); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($report['rows'] === []): ?>
                    <tr><td colspan="<?= count($report['headers']); ?>">Tidak ada data.</td></tr>
                <?php else: ?>
                    <?php foreach ($report['rows'] as $row): ?>
                        <tr>
                            <?php foreach ($report['headers'] as $key => $header): ?>
                                <?php $keys = array_keys($row); $value = $row[$keys[$key]] ?? null; ?>
                                <td><?= $value === null ? '-' : e((string) $value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="sign">
            <div class="sign-box">
                <?= e(setting_value('letter_city', ''), ); ?>, <?= e(format_tanggal(date('Y-m-d'))); ?><br>
                Kepala Sarpras,
                <div class="sign-space"></div>
                <strong><?= e(setting_value('sarpras_head_name', 'Kepala Sarpras')); ?></strong><br>
                NIP. <?= e(setting_value('sarpras_head_nip', '-')); ?>
            </div>
        </div>
    </div>
</body>
</html>