<?php

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Database;

$tables = Database::fetchAll('SHOW TABLES');
echo "Database: " . config('database.name') . PHP_EOL;
echo "Tables: " . count($tables) . PHP_EOL;
foreach ($tables as $row) {
    echo '  - ' . array_values($row)[0] . PHP_EOL;
}

$userTypes = Database::fetchAll('SELECT id, type_name FROM user_type');
echo "user_type seed rows: " . count($userTypes) . PHP_EOL;
