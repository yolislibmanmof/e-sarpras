<?php

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

/*
|--------------------------------------------------------------------------
| Memuat file .env
|--------------------------------------------------------------------------
*/
(function (): void {
    $file = BASE_PATH . '/.env';
    if (!is_file($file)) {
        return;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        if ($value !== '' && ($value[0] === '"' || $value[0] === "'")) {
            $quote = $value[0];
            if (substr($value, -1) === $quote) {
                $value = substr($value, 1, -1);
            }
        }
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
})();

/*
|--------------------------------------------------------------------------
| Autoloader PSR-4 sederhana: App\ => app/
|--------------------------------------------------------------------------
*/
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file     = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

/*
|--------------------------------------------------------------------------
| Helper inti framework
|--------------------------------------------------------------------------
*/
if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        static $cache = [];
        $segments = explode('.', $key);
        $file     = $segments[0];
        if (!array_key_exists($file, $cache)) {
            $path         = BASE_PATH . '/config/' . $file . '.php';
            $cache[$file] = is_file($path) ? require $path : [];
        }
        $value = $cache[$file];
        foreach (array_slice($segments, 1) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return BASE_PATH . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = defined('BASE_URL') ? BASE_URL : '';
        return $base . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('admin_url')) {
    function admin_url(string $path = ''): string
    {
        $base = defined('ADMIN_URL') ? ADMIN_URL : base_url('admin');
        return $base . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        return base_url('assets/public/' . ltrim($path, '/'));
    }
}

if (!function_exists('admin_asset_url')) {
    function admin_asset_url(string $path): string
    {
        return admin_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('current_uri')) {
    function current_uri(): string
    {
        $path  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $entry = defined('ENTRY_DIR') ? ENTRY_DIR : '';
        if ($entry !== '' && strpos($path, $entry) === 0) {
            $path = substr($path, strlen($entry));
        }
        $path = '/' . trim((string) $path, '/');
        return $path === '//' ? '/' : $path;
    }
}

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header('Location: ' . $url, true, 302);
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Konfigurasi runtime
|--------------------------------------------------------------------------
*/
date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));

if (config('app.debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

/*
|--------------------------------------------------------------------------
| Menyiapkan direktori storage
|--------------------------------------------------------------------------
*/
foreach (['storage/logs', 'storage/cache', 'storage/uploads', 'storage/exports', 'storage/backups'] as $dir) {
    $path = BASE_PATH . '/' . $dir;
    if (!is_dir($path)) {
        @mkdir($path, 0775, true);
    }
}

/*
|--------------------------------------------------------------------------
| Memuat bootstrap lanjutan
|--------------------------------------------------------------------------
*/
require_once BASE_PATH . '/bootstrap/helpers.php';
require_once BASE_PATH . '/bootstrap/database.php';
require_once BASE_PATH . '/bootstrap/security.php';
require_once BASE_PATH . '/bootstrap/session.php';