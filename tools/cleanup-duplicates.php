<?php

/**
 * Remove duplicate / legacy files left over from the public/ migration.
 * Run: php tools/cleanup-duplicates.php
 *
 * Keeps root *.php wrappers (backward-compatible URLs when docroot is project root).
 * Canonical web assets live in public/assets/ only.
 */

$root = dirname(__DIR__);

$removeFiles = [
    'about2.css',
    'adminDash.css',
    'bootstrap.css',
    'style.css',
    'invoice.css',
    'bootstrap.js',
    'bootstrap.bundle.js',
    'adminheader.php',
];

$removeDirs = [
    'assets',
    'font',
];

foreach ($removeFiles as $file) {
    $path = $root . '/' . $file;
    if (!is_file($path)) {
        continue;
    }
    unlink($path);
    echo "Removed file: {$file}\n";
}

foreach ($removeDirs as $dir) {
    $path = $root . '/' . $dir;
    if (!is_dir($path)) {
        continue;
    }
    removeTree($path);
    echo "Removed directory: {$dir}/\n";
}

echo "Done.\n";

function removeTree(string $dir): void
{
    $items = scandir($dir);
    if ($items === false) {
        return;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            removeTree($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}
