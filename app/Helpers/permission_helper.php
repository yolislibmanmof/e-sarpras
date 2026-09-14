<?php

declare(strict_types=1);

if (!function_exists('can')) {
    function can(string $code): bool
    {
        return \App\Security\Auth::can($code);
    }
}