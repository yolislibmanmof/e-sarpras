<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Response;
use App\Security\Auth;

class GuestMiddleware
{
    public function handle(?string $param = null): void
    {
        if (Auth::check()) {
            Response::redirect(admin_url('/dashboard'));
        }
    }
}