<?php

declare(strict_types=1);

namespace App\Services;

class LetterDesign
{
    public static function defaults(): array
    {
        return [
            'font_family'     => 'Times New Roman',
            'font_size'       => '12',
            'line_height'     => '1.6',
            'space_after_kop' => '10',
            'space_paragraph' => '6',
            'margin_top'      => '20',
            'margin_side'     => '20',
            'show_logo'       => '1',
            'kop_dept'        => 'BAGIAN SARANA DAN PRASARANA',
            'kop_border'      => 'double',
            'show_meta'       => '1',
            'sign_title'      => 'Kepala Sarpras',
            'show_nip'        => '1',
            'show_paraf'      => '1',
        ];
    }

    public static function current(): array
    {
        $raw  = setting_value('letter_design', '');
        $data = json_decode($raw ?: '[]', true);
        if (!is_array($data)) { $data = []; }
        return array_merge(self::defaults(), $data);
    }

    public static function borderCss(string $type): string
    {
        return [
            'double' => '3px double #000',
            'thick'  => '4px solid #000',
            'thin'   => '1.5px solid #000',
        ][$type] ?? '3px double #000';
    }
}