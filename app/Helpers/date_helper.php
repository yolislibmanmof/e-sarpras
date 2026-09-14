<?php

declare(strict_types=1);

if (!function_exists('format_tanggal')) {
    function format_tanggal(?string $date): string
    {
        if ($date === null || $date === '') {
            return '-';
        }
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $ts = strtotime($date);
        if ($ts === false) {
            return '-';
        }
        return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts) - 1] . ' ' . date('Y', $ts);
    }
}

if (!function_exists('format_tanggal_waktu')) {
    function format_tanggal_waktu(?string $date): string
    {
        if ($date === null || $date === '') {
            return '-';
        }
        $ts = strtotime($date);
        if ($ts === false) {
            return '-';
        }
        return format_tanggal($date) . ' ' . date('H:i', $ts);
    }
}

if (!function_exists('format_tanggal_panjang')) {
    function format_tanggal_panjang(?string $date): string
    {
        if ($date === null || $date === '') {
            return '-';
        }
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $ts = strtotime($date);
        if ($ts === false) {
            return '-';
        }
        return $hari[(int) date('w', $ts)] . ', ' . (int) date('j', $ts) . ' ' . $bulan[(int) date('n', $ts) - 1] . ' ' . date('Y', $ts);
    }
}