<?php

declare(strict_types=1);

if (!function_exists('format_rupiah')) {
    function format_rupiah($value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }
}

if (!function_exists('str_limit')) {
    function str_limit(string $value, int $limit = 100, string $end = '...'): string
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        return mb_substr($value, 0, $limit) . $end;
    }
}

if (!function_exists('status_badge_class')) {
    function status_badge_class(string $status): string
    {
        $status = strtolower($status);
        if (strpos($status, 'selesai') !== false || strpos($status, 'baik') !== false || strpos($status, 'aktif') !== false || strpos($status, 'disetujui') !== false) {
            return 'badge-success';
        }
        if (strpos($status, 'proses') !== false || strpos($status, 'menunggu') !== false || strpos($status, 'terjadwal') !== false || strpos($status, 'diperbaiki') !== false) {
            return 'badge-warning';
        }
        if (strpos($status, 'tolak') !== false || strpos($status, 'rusak berat') !== false || strpos($status, 'batal') !== false) {
            return 'badge-danger';
        }
        return 'badge-info';
    }
}