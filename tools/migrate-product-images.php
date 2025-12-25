<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Database;

$sql = file_get_contents(dirname(__DIR__) . '/database/migrations/001_product_images.sql');
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $stmt) {
    if ($stmt === '' || stripos($stmt, 'USE ') === 0) {
        continue;
    }
    try {
        Database::connection()->query($stmt);
        echo "OK\n";
    } catch (Throwable $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
}

echo "Migration complete.\n";
