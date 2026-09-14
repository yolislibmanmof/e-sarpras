<?php

declare(strict_types=1);

if (!function_exists('db')) {
    function db(): \App\Core\Database
    {
        return \App\Core\Database::instance();
    }
}