<?php

declare(strict_types=1);

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        return \App\Security\Auth::user();
    }
}

if (!function_exists('auth_id')) {
    function auth_id(): ?int
    {
        return \App\Security\Auth::id();
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return \App\Security\Auth::check();
    }
}