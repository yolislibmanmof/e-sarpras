<?php

declare(strict_types=1);

namespace App\Security;

use App\Core\Session;

class Auth
{
    private static ?array $cachedUser = null;

    public static function check(): bool
    {
        return Session::has('admin_user_id');
    }

    public static function id(): ?int
    {
        $id = Session::get('admin_user_id');
        return $id === null ? null : (int) $id;
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        if (self::$cachedUser !== null) {
            return self::$cachedUser;
        }

        $user = db()->selectOne("SELECT * FROM users WHERE id = ? LIMIT 1", [self::id()]);
        if ($user === null || (int) $user['is_active'] !== 1) {
            return null;
        }

        unset($user['password']);
        return self::$cachedUser = $user;
    }

    public static function roles(): array
    {
        $roles = Session::get('admin_roles', []);
        return is_array($roles) ? $roles : [];
    }

    public static function hasRole(string $code): bool
    {
        return in_array($code, self::roles(), true);
    }

    public static function can(string $code): bool
    {
        if (!self::check()) {
            return false;
        }
        return Permission::userCan(self::id(), $code);
    }

    public static function attempt(string $identifier, string $password): bool
    {
        $user = db()->selectOne(
            "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1",
            [$identifier, $identifier]
        );

        if ($user === null || (int) $user['is_active'] !== 1) {
            return false;
        }

        if (!Password::verify($password, $user['password'])) {
            return false;
        }

        Session::regenerate();
        Session::put('admin_user_id', (int) $user['id']);
        Session::put('admin_roles', array_column(Permission::userRoles((int) $user['id']), 'code'));

        db()->update('users', ['last_login_at' => date('Y-m-d H:i:s')], ['id' => $user['id']]);

        return true;
    }

    public static function logout(): void
    {
        self::$cachedUser = null;
        Session::destroy();
    }
}