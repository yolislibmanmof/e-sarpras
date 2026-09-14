<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Request;
use App\Security\Csrf;

class CsrfMiddleware
{
    public function handle(?string $param = null): void
    {
        $method = Request::method();
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return;
        }

        $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

        if (!Csrf::verify($token)) {
            http_response_code(419);
            echo 'Sesi tidak valid. Silakan muat ulang halaman dan coba lagi.';
            exit;
        }
    }
}