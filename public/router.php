<?php

/**
 * Router for PHP's built-in server (optional).
 * Usage: php -S 127.0.0.1:8888 -t public public/router.php
 *
 * Serves existing files as-is; sends directory requests to index.php.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$path = __DIR__ . $uri;

if ($uri !== '/' && is_file($path)) {
    return false;
}

if (is_dir($path) && is_file($path . '/index.php')) {
    require $path . '/index.php';
    return true;
}

if ($uri === '/' || $uri === '') {
    require __DIR__ . '/index.php';
    return true;
}

http_response_code(404);
echo '404 Not Found';
return true;
