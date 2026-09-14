<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run(): void
    {
        try {
            $method  = \App\Core\Request::method();
            $matched = $this->router->dispatch($method, current_uri());

            if (!$matched) {
                http_response_code(404);
                $this->renderError(404);
            }
        } catch (\Throwable $e) {
            $logDir = base_path('storage/logs');
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0775, true);
            }
            @file_put_contents(
                $logDir . '/app.log',
                date('Y-m-d H:i:s') . ' ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL,
                FILE_APPEND
            );

            if (config('app.debug')) {
                throw $e;
            }

            http_response_code(500);
            echo 'Terjadi kesalahan internal. Silakan coba lagi.';
        }
    }

    private function renderError(int $code): void
    {
        $entry = defined('ENTRY') ? ENTRY : 'public';
        $view  = $entry === 'admin' ? 'admin/errors/' . $code : 'public/errors/' . $code;

        if (!View::exists($view)) {
            $view = 'public/errors/' . $code;
        }

        if (View::exists($view)) {
            echo View::fetch($view, ['code' => $code]);
        } else {
            echo $code === 404 ? '404 Not Found' : 'Error';
        }
    }
}