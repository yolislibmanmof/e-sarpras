<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;

class MediaController extends Controller
{
    public function show(string $a, string $b): void
    {
        $base = rtrim(config('upload.base_path'), '/');
        $path = $base . '/' . $a . '/' . $b;

        if (strpos($b, '.') === 0 || strpos($b, '/') !== false || !is_file($path)) {
            http_response_code(404);
            echo '404';
            exit;
        }

        $ext  = strtolower(pathinfo($b, PATHINFO_EXTENSION));
        $mime = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
            'webp' => 'image/webp', 'gif' => 'image/gif', 'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
        ][$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($path));
        header('Cache-Control: public, max-age=86400');
        readfile($path);
        exit;
    }
}