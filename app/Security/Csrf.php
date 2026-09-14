<?php

declare(strict_types=1);

namespace App\Security;

use App\Core\Session;

class Csrf
{
    public static function token(): string
    {
        $token = Session::get('_csrf_token');
        if ($token === null) {
            $token = bin2hex(random_bytes(32));
            Session::put('_csrf_token', $token);
        }
        return $token;
    }

    public static function verify(?string $token): bool
    {
        $current = Session::get('_csrf_token');
        if ($token === null || $current === null) {
            return false;
        }
        return hash_equals($current, $token);
    }
}