<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Response;
use App\Core\Session;
use App\Security\Auth;

class PermissionMiddleware
{
    public function handle(?string $param = null): void
    {
        if ($param === null) {
            return;
        }

        if (!Auth::can($param)) {
            Session::flash('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            Response::redirect(admin_url('/dashboard'));
        }
    }
}