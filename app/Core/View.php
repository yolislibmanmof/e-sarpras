<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function exists(string $view): bool
    {
        return is_file(base_path('views/' . $view . '.php'));
    }

    public static function fetch(string $view, array $data = []): string
    {
        $file = base_path('views/' . $view . '.php');
        if (!is_file($file)) {
            return 'View tidak ditemukan: ' . $view;
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    public static function render(string $view, array $data = [], ?string $layout = null): void
    {
        if ($layout !== null) {
            $content       = self::fetch($view, $data);
            $data['content'] = $content;
            echo self::fetch($layout, $data);
            return;
        }
        echo self::fetch($view, $data);
    }
}