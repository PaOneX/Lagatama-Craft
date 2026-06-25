<?php

/**
 * One-time reorganizer: fixes bootstrap paths in public/ and regenerates API shims.
 * Run: php tools/reorganize.php
 */

$root = dirname(__DIR__);

// public/*.php
foreach (glob($root . '/public/*.php') as $file) {
    if (in_array(basename($file), ['init.php', 'router.php'], true)) {
        continue;
    }
    fixBootstrap($file, "require_once __DIR__ . '/init.php';");
}

// public/admin/*.php
foreach (glob($root . '/public/admin/*.php') as $file) {
    fixBootstrap($file, "require_once dirname(__DIR__) . '/init.php';");
}

// public/api/*/*.php
foreach (glob($root . '/public/api/*/*.php') as $file) {
    fixBootstrap($file, "require_once dirname(__DIR__, 2) . '/init.php';");
}

passthru('php ' . escapeshellarg($root . '/tools/create-public-wrappers.php'));

echo "Done.\n";

function fixBootstrap(string $file, string $replacement): void
{
    $content = file_get_contents($file);
    $updated = preg_replace(
        '/require_once\s+[^;]+bootstrap\.php[^;]*;/',
        $replacement,
        $content,
        1
    );
    if ($updated !== null && $updated !== $content) {
        file_put_contents($file, $updated);
        echo "Fixed: {$file}\n";
    }
}
