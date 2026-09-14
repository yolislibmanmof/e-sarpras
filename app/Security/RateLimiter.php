<?php

declare(strict_types=1);

namespace App\Security;

class RateLimiter
{
    private static function path(string $key): string
    {
        return base_path('storage/cache/rate_' . md5($key) . '.json');
    }

    private static function read(string $key): array
    {
        $default = ['count' => 0, 'first_at' => time(), 'locked_until' => 0];
        $path = self::path($key);
        if (!is_file($path)) {
            return $default;
        }
        $data = json_decode((string) file_get_contents($path), true);
        if (!is_array($data)) {
            return $default;
        }
        return $data + $default;
    }

    private static function write(string $key, array $data): void
    {
        @file_put_contents(self::path($key), json_encode($data));
    }

    public static function tooManyAttempts(string $key, int $max = 5, int $minutes = 15): bool
    {
        $data = self::read($key);

        if ($data['locked_until'] > time()) {
            return true;
        }

        if (time() - $data['first_at'] > $minutes * 60) {
            self::reset($key);
            return false;
        }

        return $data['count'] >= $max;
    }

    public static function hit(string $key, int $max = 5, int $minutes = 15): int
    {
        $data = self::read($key);

        if (time() - $data['first_at'] > $minutes * 60) {
            $data = ['count' => 0, 'first_at' => time(), 'locked_until' => 0];
        }

        $data['count']++;

        if ($data['count'] >= $max) {
            $data['locked_until'] = time() + $minutes * 60;
        }

        self::write($key, $data);
        return $data['count'];
    }

    public static function reset(string $key): void
    {
        @unlink(self::path($key));
    }
}