<?php

declare(strict_types=1);

namespace App\Security;

use App\Core\Request;

class AuditLogger
{
    public static function log(string $action, string $module, $recordId = null, ?array $old = null, ?array $new = null): void
    {
        $userId = Auth::id();

        try {
            db()->insert('audit_logs', [
                'user_id'    => $userId,
                'action'     => $action,
                'module'     => $module,
                'record_id'  => $recordId === null ? null : (string) $recordId,
                'old_values' => $old === null ? null : json_encode($old, JSON_UNESCAPED_UNICODE),
                'new_values' => $new === null ? null : json_encode($new, JSON_UNESCAPED_UNICODE),
                'ip_address' => Request::ip(),
            ]);
        } catch (\Throwable $e) {
            // Kegagalan audit tidak boleh menghentikan proses utama.
        }

        @file_put_contents(
            base_path('storage/logs/audit.log'),
            date('Y-m-d H:i:s') . ' | ' . $action . ' | ' . $module . ' | user:' . ($userId ?? '-') . PHP_EOL,
            FILE_APPEND
        );
    }
}