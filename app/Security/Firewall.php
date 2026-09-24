<?php

declare(strict_types=1);

namespace App\Security;

/**
 * Firewall aplikasi: memblokir metode aneh, bot pemindai, pola
 * SQLi/XSS/traversal/injeksi perintah, serta membatasi laju request
 * per IP. Seluruh penolakan dicatat ke storage/logs/firewall.log.
 */
class Firewall
{
    private const BAD_UA = [
        'sqlmap', 'nikto', 'nmap', 'masscan', 'acunetix', 'w3af',
        'nessus', 'openvas', 'dirbuster', 'gobuster', 'wfuzz', 'hydra', 'zgrab',
    ];

    private const BAD_PATTERNS = [
        '/union[\s+\/\*]+select/i',
        '/select[\s+\/\*]+[a-z0-9_\s\*\(\),]+from[\s+\/\*]+information_schema/i',
        '/(drop|truncate|alter)[\s+\/\*]+(table|database|schema)/i',
        '/<script[\s>]/i',
        '/javascript[\s]*:/i',
        '/on(error|load|click|mouseover|focus)[\s]*=/i',
        '/\.\.\/\.\.\//',
        '/(etc\/passwd|win\.ini|cmd\.exe|\/bin\/bash|\/bin\/sh)/i',
        '/(\||;|`)\s*(cat|ls|dir|whoami|wget|curl|nc|netcat|bash|sh|powershell)/i',
        '/(base64_decode|eval|system|exec|passthru|shell_exec)[\s]*\(/i',
    ];

    public static function inspect(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (!in_array($method, ['GET', 'POST', 'HEAD', 'OPTIONS'], true)) {
            self::deny('Metode tidak diizinkan');
        }

        $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
        foreach (self::BAD_UA as $bad) {
            if ($ua !== '' && strpos($ua, $bad) !== false) {
                self::deny('Bot pemindai terlarang');
            }
        }

        $inputs = array_merge(self::flatten($_GET), self::flatten($_POST), self::flatten($_COOKIE));
        foreach ($inputs as $value) {
            foreach (self::BAD_PATTERNS as $pattern) {
                if (preg_match($pattern, $value) === 1) {
                    self::deny('Pola permintaan mencurigakan');
                }
            }
        }

        self::throttle();
    }

    private static function flatten(array $input, int $depth = 0): array
    {
        $out = [];
        if ($depth > 3) { return $out; }
        foreach ($input as $v) {
            if (is_array($v)) {
                $out = array_merge($out, self::flatten($v, $depth + 1));
            } elseif (is_string($v)) {
                $out[] = strlen($v) > 2000 ? substr($v, 0, 2000) : $v;
            }
        }
        return $out;
    }

    private static function throttle(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $dir = base_path('storage/firewall');
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $file = $dir . '/' . md5($ip) . '.txt';
        $now = time();
        $raw = @file_get_contents($file);
        $start = $now; $count = 0;
        if ($raw !== false && strpos($raw, '|') !== false) {
            [$start, $count] = array_map('intval', explode('|', $raw));
        }
        if ($now - $start >= 10) { $start = $now; $count = 0; }
        $count++;
        @file_put_contents($file, $start . '|' . $count);
        if ($count > 120) {
            self::deny('Terlalu banyak permintaan', 429);
        }
    }

    private static function deny(string $reason, int $code = 403): void
    {
        $log = base_path('storage/logs/firewall.log');
        @file_put_contents(
            $log,
            date('c') . ' | ' . ($_SERVER['REMOTE_ADDR'] ?? '-') . ' | ' . $reason . ' | ' . ($_SERVER['REQUEST_METHOD'] ?? '-') . ' ' . ($_SERVER['REQUEST_URI'] ?? '-') . PHP_EOL,
            FILE_APPEND
        );
        http_response_code($code);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-store');
        echo 'Akses ditolak oleh sistem keamanan.';
        exit;
    }
}