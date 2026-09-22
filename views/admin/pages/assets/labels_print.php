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
        .label-campus { font-size: 8pt; font-weight: 600; }
        .label-code { font-weight: 700; font-size: 12pt; letter-spacing: 1px; margin: 2mm 0; }
        .label-name { font-size: 9pt; margin-bottom: 2mm; }
        .label-qr { display: flex; justify-content: center; margin: 2mm 0; }
        .label-qr img { width: 48px; height: 48px; background: #fff; border: 1px solid #e5e7eb; border-radius: 4px; padding: 2px; }
        .label-meta { font-size: 7pt; margin-top: 2mm; color: #334155; word-break: break-all; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" onclick="window.print()">Cetak Sekarang</button>
    </div>

    <?php
    $qrUrl = qr_url(base_url('/aset-profil/' . (int) $asset['id']));
    $profileUrl = base_url('/aset-profil/' . (int) $asset['id']);
    ?>

    <div class="label-sheet">
        <?php foreach ($labels as $label): ?>
            <div class="label-box">
                <div class="label-campus"><?= e($campus_name); ?></div>
                <div class="label-code"><?= e($label['label_code']); ?></div>
                <div class="label-name"><?= e($asset['name']); ?></div>
                <div class="label-qr"><img src="<?= e($qrUrl); ?>" alt="QR"></div>
                <div class="label-meta"><?= e($label['qr_code']); ?><br><?= e($profileUrl); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>