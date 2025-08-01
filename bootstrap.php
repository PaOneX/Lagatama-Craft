<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__);

if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
} else {
    spl_autoload_register(static function (string $class): void {
        if (str_starts_with($class, 'App\\')) {
            $file = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }
    });
}

if (file_exists(BASE_PATH . '/.env')) {
    if (class_exists(\Dotenv\Dotenv::class)) {
        $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();
    } else {
        $lines = file(BASE_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, " \t\"'");
        }
    }
}

$config = [
    'app' => require BASE_PATH . '/config/app.php',
    'database' => require BASE_PATH . '/config/database.php',
    'payhere' => require BASE_PATH . '/config/payhere.php',
    'mail' => require BASE_PATH . '/config/mail.php',
    'google' => require BASE_PATH . '/config/google.php',
    'promotions' => require BASE_PATH . '/config/promotions.php',
    'security' => require BASE_PATH . '/config/security.php',
];

$GLOBALS['config'] = $config;

date_default_timezone_set($config['app']['timezone']);

if (session_status() === PHP_SESSION_NONE) {
    \App\Core\Security::configureSession();
    session_start();
}

\App\Core\Security::boot();

function config(string $key, mixed $default = null): mixed
{
    $parts = explode('.', $key);
    $value = $GLOBALS['config'];

    foreach ($parts as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $default;
        }
        $value = $value[$part];
    }

    return $value;
}

function view(string $path, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require BASE_PATH . '/views/' . $path . '.php';
}

function base_path(string $path = ''): string
{
    return BASE_PATH . ($path ? '/' . ltrim($path, '/') : '');
}

function public_path(string $path = ''): string
{
    return BASE_PATH . '/public' . ($path ? '/' . ltrim($path, '/') : '');
}

function web_base(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    if (($pos = strpos($script, '/public/')) !== false) {
        return substr($script, 0, $pos + strlen('/public'));
    }

    if (str_contains($script, '/admin/')) {
        $base = str_replace('\\', '/', dirname(dirname($script)));
        return in_array($base, ['/', '.', ''], true) ? '' : $base;
    }

    $dir = str_replace('\\', '/', dirname($script));
    return in_array($dir, ['/', '.'], true) ? '' : $dir;
}

function asset(string $path): string
{
    return web_base() . '/assets/' . ltrim($path, '/');
}

function resource_url(string $path): string
{
    return web_base() . '/resources/' . ltrim($path, '/');
}

function csrf_url(): string
{
    return web_base() . '/csrf.php';
}

function media_url(string $path): string
{
    return web_base() . '/' . ltrim($path, '/');
}

function admin_layout(string $pageTitle, string $activeNav, callable $render, array $options = []): void
{
    \App\Core\Security::preventCaching();

    if (!\App\Core\Auth::checkAdmin()) {
        \App\Core\Response::redirect('adminSignIn.php');
    }

    $adminData = \App\Core\Auth::admin();
    $extraHead = $options['extraHead'] ?? null;
    $extraScripts = $options['extraScripts'] ?? null;
    $onload = $options['onload'] ?? null;
    $bodyClass = $options['bodyClass'] ?? null;

    ob_start();
    $render();
    $content = (string) ob_get_clean();

    require base_path('views/layouts/admin.php');
}
