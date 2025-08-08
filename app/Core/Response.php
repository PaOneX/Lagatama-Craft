<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    public static function json(mixed $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function text(string $message, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: text/plain; charset=utf-8');
        echo $message;
        exit;
    }

    public static function html(string $html, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }

    public static function redirect(string $url): never
    {
        Security::preventCaching();
        header('Location: ' . $url);
        exit;
    }
}
