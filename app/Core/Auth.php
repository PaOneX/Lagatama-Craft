<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private static ?array $resolvedAdmin = null;
    private static bool $adminResolved = false;

    public static function user(): ?array
    {
        return Session::get('u');
    }

    public static function admin(): ?array
    {
        return self::resolveAdmin();
    }

    public static function checkUser(): bool
    {
        return Session::has('u');
    }

    public static function checkAdmin(): bool
    {
        return self::resolveAdmin() !== null;
    }

    public static function resolveAdmin(): ?array
    {
        if (self::$adminResolved) {
            return self::$resolvedAdmin;
        }

        self::$adminResolved = true;
        $stored = Session::get('a');

        if (!is_array($stored)) {
            return null;
        }

        $id = (int) ($stored['id'] ?? 0);
        if ($id <= 0) {
            self::logoutAdmin();
            return null;
        }

        $user = User::findById($id);
        if ($user === null || (int) $user['user_type_id'] !== 1 || (int) $user['status'] !== 1) {
            self::logoutAdmin();
            return null;
        }

        Session::set('a', $user);
        self::$resolvedAdmin = $user;

        return $user;
    }

    public static function requireUser(bool $json = true): array
    {
        $user = self::user();
        if ($user === null) {
            if ($json) {
                Response::text('Unauthorized', 401);
            }
            Response::redirect('index.php');
        }
        return $user;
    }

    public static function requireAdmin(bool $json = true): array
    {
        $admin = self::resolveAdmin();
        if ($admin === null) {
            if ($json) {
                Response::text('Unauthorized', 401);
            }
            Response::redirect('adminSignIn.php');
        }
        return $admin;
    }

    public static function loginUser(array $user): void
    {
        Session::regenerate();
        Session::set('u', $user);
    }

    public static function loginAdmin(array $admin): void
    {
        Session::regenerate();
        Session::set('a', $admin);
        self::$adminResolved = false;
        self::$resolvedAdmin = null;
    }

    public static function logoutUser(): void
    {
        Session::remove('u');
    }

    public static function logoutAdmin(): void
    {
        Session::remove('a');
        self::$adminResolved = false;
        self::$resolvedAdmin = null;
    }

    public static function verifyCsrfOrFail(): void
    {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!Session::verifyCsrf(is_string($token) ? $token : null)) {
            Response::text('Invalid CSRF token', 403);
        }
    }
}
