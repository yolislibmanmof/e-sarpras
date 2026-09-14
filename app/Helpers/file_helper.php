<?php

declare(strict_types=1);

if (!function_exists('format_bytes')) {
    function format_bytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

if (!function_exists('safe_filename')) {
    function safe_filename(string $name): string
    {
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        return bin2hex(random_bytes(16)) . time() . '.' . preg_replace('/[^a-z0-9]/', '', $ext);
    }
}