<?php $d = \App\Services\LetterDesign::current(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat <?= e($letter['letter_number'] ?? ''); ?></title>
    <style>
        body { font-family: "<?= e($d['font_family']); ?>", serif; color: #000; margin: 0; font-size: <?= (int) $d['font_size']; ?>pt; }
        .sheet { width: 210mm; min-height: 297mm; margin: 0 auto; padding: <?= (int) $d['margin_top']; ?>mm <?= (int) $d['margin_side']; ?>mm; line-height: <?= (float) $d['line_height']; ?>; }
        .kop { display: flex; align-items: center; gap: 14px; padding-bottom: 10px; border-bottom: <?= \App\Services\LetterDesign::borderCss($d['kop_border']); ?>; margin-bottom: <?= (int) $d['space_after_kop']; ?>mm; }
        .kop img { width: 70px; height: 70px; object-fit: contain; }
        .kop-logo-text { width: 70px; height: 70px; border: 2px solid #000; display: flex; align-items: center; justify-content: center; font-weight: bold; font-family: Arial, sans-serif; font-size: 10pt; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .l1 { font-size: 1.25em; font-weight: bold; }
        .kop-text .l2 { font-size: 1.05em; font-weight: bold; }
        .kop-text .l3 { font-size: 0.8em; line-height: 1.5; }
        .meta { margin: 0 0 <?= (int) $d['space_paragraph']; ?>mm; }
        .meta table td { padding: 1px 6px 1px 0; }
        .p { margin: 0 0 <?= (int) $d['space_paragraph']; ?>mm; white-space: pre-wrap; }
        .sign { margin-top: 2em; display: flex; justify-content: flex-end; }
        .sign-box { text-align: center; }
        .sign-space { height: 55px; }
        .no-print { padding: 10px 0; text-align: center; }
        .no-print button { padding: 8px 18px; cursor: pointer; }
        @media print { .no-print { display: none; } .sheet { margin: 0; } }
    </style>
</head>
<body>
    <div class="no-print"><button type="button" onclick="window.print()">Cetak / Simpan PDF</button></div>

    <div class="sheet">
        <div class="kop" style="margin-bottom:<?= (int) $d['space_after_kop']; ?>mm;">
            <?php if ($d['show_logo'] === '1'): ?>
                <?php if ($hasLogo): ?>
                    <img src="<?= e($logoUrl); ?>" alt="Logo">
                <?php else: ?>
                    <div class="kop-logo-text">LOGO</div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="kop-text">
                <div class="l1"><?= e(setting_value('campus_name', 'Kampus')); ?></div>
                <div class="l2"><?= e($d['kop_dept']); ?></div>
                <div class="l3">
                    <?= e($kop['address'] ?? ''); ?><br>
                    <?php $contacts = array_filter([
                        ($kop['phone']   ?? '') !== '' ? 'Telp. ' . $kop['phone']   : '',
                        ($kop['email']   ?? '') !== '' ? 'Email: ' . $kop['email']   : '',
                        ($kop['website'] ?? '') !== '' ? 'Web: '   . $kop['website'] : '',
                    ]); ?>
                    <?= implode(' &middot; ', $contacts); ?>
                </div>
            </div>
        </div>

        <?php if ($d['show_meta'] === '1'): ?>
        <div class="meta">
            <table>
                <tr><td>Nomor</td><td>: <?= e($letter['letter_number'] ?? ''); ?></td></tr>
                <tr><td>Lampiran</td><td>: 1 berkas</td></tr>
                <tr><td>Perihal</td><td>: <strong><?= e($letter['subject'] ?? ''); ?></strong></td></tr>
            </table>
        </div>
        <?php endif; ?>

        <div class="p"><?= e($letter['content'] ?? ''); ?></div>

        <div class="sign">
            <div class="sign-box">
                <?= e(setting_value('letter_city', 'Kota')); ?>, <?= e(format_tanggal($letter['letter_date'] ?? date('Y-m-d'))); ?><br>
                <?= e($d['sign_title']); ?>,
                <?php if ($d['show_paraf'] === '1'): ?>
                    <div class="sign-space"></div>
                <?php else: ?>
                    <div style="height:8px;"></div>
                <?php endif; ?>
                <strong><?= e(setting_value('sarpras_head_name', 'Kepala Sarpras')); ?></strong><br>
                <?php if ($d['show_nip'] === '1'): ?>
                    NIP. <?= e(setting_value('sarpras_head_nip', '-')); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>