<?php

/**
 * Remove root-level PHP shim files (public/ is the only web entry point).
 * Run: php tools/remove-root-wrappers.php
 */

$root = dirname(__DIR__);

$keep = ['bootstrap.php'];

$removed = 0;

foreach (glob($root . '/*.php') as $file) {
    $name = basename($file);
    if (in_array($name, $keep, true)) {
        continue;
    }
    unlink($file);
    echo "Removed: {$name}\n";
    $removed++;
}

$apiDir = $root . '/api';
if (is_dir($apiDir)) {
    foreach (glob($apiDir . '/*/*.php') ?: [] as $file) {
        unlink($file);
        echo 'Removed: api/' . basename(dirname($file)) . '/' . basename($file) . "\n";
        $removed++;
    }
    @rmdir($apiDir . '/checkout');
    @rmdir($apiDir);
}

$extras = ['script.js', 'index.html'];
foreach ($extras as $file) {
    $path = $root . '/' . $file;
    if (!is_file($path)) {
        continue;
    }
    unlink($path);
    echo "Removed: {$file}\n";
    $removed++;
}

echo "Done. {$removed} files removed.\n";
echo "Start dev server: php -S 127.0.0.1:8888 -t public\n";
