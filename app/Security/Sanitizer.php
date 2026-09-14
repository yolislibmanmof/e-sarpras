<?php

declare(strict_types=1);

namespace App\Security;

class Sanitizer
{
    public static function clean($value)
    {
        if (is_array($value)) {
            return array_map([self::class, 'clean'], $value);
        }
        if (is_string($value)) {
            return trim(strip_tags($value));
        }
        return $value;
    }

    public static function escape($value): string
    {
        return e($value);
    }
}