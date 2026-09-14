<?php

$GLOBALS['APP_MIDDLEWARE'] = [
    'auth'  => \App\Middlewares\AuthMiddleware::class,
    'guest' => \App\Middlewares\GuestMiddleware::class,
    'csrf'  => \App\Middlewares\CsrfMiddleware::class,
    'rate'  => \App\Middlewares\RateLimitMiddleware::class,
    'perm'  => \App\Middlewares\PermissionMiddleware::class,
];