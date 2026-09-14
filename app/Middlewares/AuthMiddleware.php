<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Response;
use App\Core\Session;
use App\Security\Auth;

class AuthMiddleware
{
    public function handle(?string $param = null): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'Silakan masuk terlebih dahulu.');
            Response::redirect(admin_url('/login'));
        }
    }
}