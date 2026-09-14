<?php

declare(strict_types=1);

namespace App\Security;

class Permission
{
    public static function userCan(int $userId, string $code): bool
    {
        $row = db()->selectOne(
            "SELECT COUNT(*) AS total
             FROM role_permissions rp
             JOIN user_roles ur ON ur.role_id = rp.role_id
             JOIN permissions p ON p.id = rp.permission_id
             WHERE ur.user_id = ? AND p.code = ?",
            [$userId, $code]
        );
        return (int) ($row['total'] ?? 0) > 0;
    }

    public static function userRoles(int $userId): array
    {
        return db()->select(
            "SELECT r.* FROM roles r
             JOIN user_roles ur ON ur.role_id = r.id
             WHERE ur.user_id = ?
             ORDER BY r.id",
            [$userId]
        );
    }
}