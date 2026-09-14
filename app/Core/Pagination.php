<?php

declare(strict_types=1);

namespace App\Core;

class Pagination
{
    public static function make(int $total, int $perPage, int $current, string $baseUrl): array
    {
        $last    = max(1, (int) ceil($total / $perPage));
        $current = max(1, min($current, $last));
        $links   = [];

        for ($i = 1; $i <= $last; $i++) {
            $separator = (strpos($baseUrl, '?') === false) ? '?' : '&';
            $links[] = [
                'page'   => $i,
                'url'    => $baseUrl . $separator . 'page=' . $i,
                'active' => $i === $current,
            ];
        }

        return [
            'total'   => $total,
            'per_page'=> $perPage,
            'current' => $current,
            'last'    => $last,
            'links'   => $links,
        ];
    }
}