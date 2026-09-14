<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Security\RateLimiter;

class RateLimitMiddleware
{
    public function handle(?string $param = null): void
    {
        $key     = ($param ?? 'global') . ':' . Request::ip();
        $max     = (int) config('security.login_max_attempts', 5);
        $minutes = (int) config('security.login_lockout_minutes', 15);

        if (RateLimiter::tooManyAttempts($key, $max, $minutes)) {
            Session::flash('error', 'Terlalu banyak percobaan. Silakan coba lagi dalam ' . $minutes . ' menit.');
            Response::redirect(admin_url('/login'));
        }
    }
}