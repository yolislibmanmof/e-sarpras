<?php

declare(strict_types=1);

/* QR Code offline-friendly:
   - Percobaan pertama mengambil SVG dari layanan (butuh internet SEKALI).
   - Hasil di-CACHE di storage/uploads/cache sehingga seterusnya offline.
   - Bila offline saat pertama, fallback ke barcode CODE-39 (selalu offline). */

function qr_relative(string $data): string
{
    $key  = 'qr_' . md5($data) . '.svg';
    $base = rtrim(config('upload.base_path'), '/') . '/cache';
    if (!is_dir($base)) { @mkdir($base, 0775, true); }
    $path = $base . '/' . $key;

    if (is_file($path)) { return 'cache/' . $key; }

    $svg = '';
    $url = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&format=svg&margin=0&qzone=1&data=' . urlencode($data);
    $raw = @file_get_contents($url);
    if ($raw !== false && strpos($raw, '<svg') !== false) {
        $svg = $raw;
    } else {
        $svg = code39_svg($data);
    }

    @file_put_contents($path, $svg);
    return 'cache/' . $key;
}

function qr_url(string $data): string
{
    return base_url('/media/' . qr_relative($data));
}

/* Barcode CODE-39 (fallback offline, dapat dipindai scanner 1D) */
function code39_svg(string $data, int $height = 90): string
{
    $table = [
        '0'=>'111331311','1'=>'311311113','2'=>'113311113','3'=>'313311111','4'=>'111331113',
        '5'=>'311331111','6'=>'113331111','7'=>'111311313','8'=>'311311311','9'=>'113311311',
        'A'=>'311113111','B'=>'113113111','C'=>'313113111','D'=>'111133111','E'=>'311133111',
        'F'=>'113133111','G'=>'111113311','H'=>'311113311','I'=>'113113311','J'=>'111133311',
        'K'=>'311111131','L'=>'113111131','M'=>'313111131','N'=>'111131131','O'=>'311131131',
        'P'=>'113131131','Q'=>'111111331','R'=>'311111331','S'=>'113111331','T'=>'111131331',
        'U'=>'331111111','V'=>'133111111','W'=>'333111111','X'=>'131131111','Y'=>'331131111',
        'Z'=>'133131111','-'=>'131111311','.'=>'331111311',' '=>'133111311','*'=>'131131311',
        '$'=>'131313111','/'=>'131311131','+'=>'131113131','%'=>'111313131',
    ];

    $data = strtoupper($data);
    $data = preg_replace('/[^0-9A-Z\-\.\ \$\/\+%]/', '', $data);
    $seq  = '*' . $data . '*';

    $x = 10; $module = 1; $bars = '';
    for ($i = 0; $i < strlen($seq); $i++) {
        $pat = $table[$seq[$i]] ?? $table['-'];
        for ($p = 0; $p < 9; $p++) {
            $w = (int) $pat[$p] * $module;
            if ($p % 2 === 0) { $bars .= '<rect x="' . $x . '" y="0" width="' . $w . '" height="' . $height . '" fill="#000"/>'; }
            $x += $w;
        }
        $x += 1 * $module; /* spasi antar karakter */
    }

    $width = $x + 10;
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $width . ' ' . $height . '" width="' . $width . '" height="' . $height . '"><rect width="100%" height="100%" fill="#fff"/>' . $bars . '</svg>';
}