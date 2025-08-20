<?php

/**
 * Bootstrap loader for files inside public/ directory.
 * Usage: require_once __DIR__ . '/init.php';  (from public/)
 *        require_once dirname(__DIR__) . '/public/init.php'; (from public/admin/)
 */
require_once dirname(__DIR__) . '/bootstrap.php';

define('PUBLIC_ROOT', dirname(__DIR__) . '/public');

function public_asset(string $path): string
{
    return 'assets/' . ltrim($path, '/');
}

function public_resource(string $path): string
{
    return 'resources/' . ltrim($path, '/');
}
