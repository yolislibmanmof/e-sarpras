<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label Aset</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .no-print { padding: 10mm 10mm 0; }
        .no-print button { padding: 8px 16px; cursor: pointer; }
        .label-sheet { display: flex; flex-wrap: wrap; gap: 8mm; padding: 10mm; }
        .label-box { width: 62mm; border: 1.5px solid #000; border-radius: 4px; padding: 4mm; text-align: center; }
        .label-campus { font-size: 8pt; }
        .label-code { font-weight: 700; font-size: 12pt; letter-spacing: 1px; margin: 2mm 0; }
        .label-name { font-size: 9pt; }
        .label-meta { font-size: 7pt; margin-top: 2mm; word-break: break-all; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" onclick="window.print()">Cetak Sekarang</button>
    </div>

    <div class="label-sheet">
        <?php foreach ($labels as $label): ?>
            <div class="label-box">
                <div class="label-campus"><?= e($campus_name); ?></div>
                <div class="label-code"><?= e($label['label_code']); ?></div>
                <div class="label-name"><?= e($asset['name']); ?></div>
                <div class="label-meta"><?= e($label['qr_code']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>