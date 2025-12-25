<?php

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Database;

$sql = file_get_contents(dirname(__DIR__) . '/database/migrations/003_landing_page.sql');
if ($sql === false) {
    fwrite(STDERR, "Migration file not found.\n");
    exit(1);
}

$statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));

foreach ($statements as $statement) {
    if ($statement === '' || str_starts_with($statement, '--') || strtoupper($statement) === 'USE `LAGATAMA_CRAFT`') {
        continue;
    }

    try {
        Database::query($statement);
        echo "OK: " . substr(str_replace(["\r", "\n"], ' ', $statement), 0, 80) . "...\n";
    } catch (Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n";
    }
}

echo "Done.\n";
