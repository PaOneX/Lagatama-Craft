<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function csrfToken(): string
    {
        if (!self::has('_csrf')) {
            self::set('_csrf', bin2hex(random_bytes(32)));
        }
        return (string) self::get('_csrf');
    }

    public static function verifyCsrf(?string $token): bool
    {
        $stored = self::get('_csrf');
        if ($token === null || $token === '' || $stored === null) {
            return false;
        }
        return hash_equals((string) $stored, $token);
    }
}
