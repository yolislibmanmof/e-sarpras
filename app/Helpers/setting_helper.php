<?php

declare(strict_types=1);

if (!function_exists('setting_value')) {
    function setting_value(string $key, $default = null)
    {
        static $cache = null;

        if ($cache === null) {
            $cache = [];
            try {
                $rows = db()->select("SELECT `key`, `value` FROM settings");
                foreach ($rows as $row) {
                    $cache[$row['key']] = $row['value'];
                }
            } catch (\Throwable $e) {
                // Database belum siap; gunakan nilai bawaan.
            }
        }

        return $cache[$key] ?? $default;
    }
}