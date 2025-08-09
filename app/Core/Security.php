<?php

declare(strict_types=1);

namespace App\Core;

class Security
{
    public static function boot(): void
    {
        self::enforceIdleTimeout();
        self::applyHeaders();

        if (Auth::checkUser() || Auth::checkAdmin()) {
            self::preventCaching();
        }
    }

    public static function configureSession(): void
    {
        $appUrl = config('app.url', 'http://localhost');
        $secure = str_starts_with($appUrl, 'https://');

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    public static function applyHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        header('Cross-Origin-Opener-Policy: same-origin-allow-popups');
    }

    public static function preventCaching(): void
    {
        if (headers_sent()) {
            return;
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    public static function enforceIdleTimeout(): void
    {
        $idleMinutes = (int) config('security.session_idle_minutes', 30);
        if ($idleMinutes <= 0) {
            return;
        }

        $now = time();
        $last = Session::get('_last_activity');

        if ($last !== null && ($now - (int) $last) > ($idleMinutes * 60)) {
            Auth::logoutUser();
            Auth::logoutAdmin();
            Session::remove('_last_activity');
        }

        Session::set('_last_activity', $now);
    }

    public static function rateLimit(string $action, ?string $identifier = null): bool
    {
        $maxAttempts = (int) config('security.login_max_attempts', 5);
        $lockoutMinutes = (int) config('security.login_lockout_minutes', 15);

        if ($maxAttempts <= 0 || $lockoutMinutes <= 0) {
            return true;
        }

        $identifier = $identifier ?? self::clientIp();
        $key = preg_replace('/[^a-zA-Z0-9_-]/', '_', $action . '_' . $identifier);
        $dir = BASE_PATH . '/storage/rate_limit';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir . '/' . $key . '.json';
        $windowStart = time() - ($lockoutMinutes * 60);
        $attempts = [];

        if (is_file($file)) {
            $raw = file_get_contents($file);
            $decoded = json_decode($raw ?: '[]', true);
            if (is_array($decoded)) {
                $attempts = array_values(array_filter(
                    $decoded,
                    static fn ($ts) => is_int($ts) && $ts >= $windowStart
                ));
            }
        }

        if (count($attempts) >= $maxAttempts) {
            return false;
        }

        $attempts[] = time();
        file_put_contents($file, json_encode($attempts), LOCK_EX);

        return true;
    }

    public static function clientIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return is_string($ip) ? $ip : 'unknown';
    }

    public static function isSafeHttpUrl(?string $url): bool
    {
        if ($url === null || $url === '') {
            return true;
        }

        if (preg_match('/^\s*javascript:/i', $url)) {
            return false;
        }

        $parts = parse_url($url);
        if ($parts === false) {
            return false;
        }

        if (!isset($parts['scheme'])) {
            return !str_contains($url, '://');
        }

        $scheme = strtolower($parts['scheme']);
        return in_array($scheme, ['http', 'https'], true);
    }
}
