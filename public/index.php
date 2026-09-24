<?php

declare(strict_types=1);

/* ---------- Konfigurasi error sesuai APP_DEBUG ---------- */
$appDebug = false;
$envFile = dirname(__DIR__) . '/.env';
if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos($line, 'APP_DEBUG') === 0) {
            $appDebug = strtolower(trim(substr(strstr($line, '='), 1))) === 'true';
        }
    }
}
ini_set('display_errors', $appDebug ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

/* ---------- Pengerasan cookie sesi ---------- */
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', '1');
}

/* ---------- Security headers ---------- */
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'");

/* ---------- Konstanta inti (WAJIB sama seperti versi asli) ---------- */
define('ENTRY', 'public');

$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_URL', $scriptDir);
define('ADMIN_URL', $scriptDir . '/admin');
define('ENTRY_DIR', $scriptDir);

require dirname(__DIR__) . '/bootstrap/app.php';
require dirname(__DIR__) . '/routes/middleware.php';

use App\Core\App;
use App\Core\Router;

/* ---------- Firewall aplikasi ---------- */
\App\Security\Firewall::inspect();

/* ---------- Router + rute publik ---------- */
$router = new Router();
require dirname(__DIR__) . '/routes/public.php';

(new App($router))->run();