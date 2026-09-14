<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    $lifetime = (int) config('session.lifetime', 120) * 60;

    session_name(config('session.cookie', 'e_sarpras_session'));
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path'     => '/',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => (bool) config('session.httponly', true),
        'samesite' => config('session.samesite', 'Lax'),
    ]);

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_start();
}