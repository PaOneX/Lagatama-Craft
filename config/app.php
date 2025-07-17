<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Lagatama Craft',
    'url' => rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/'),
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Colombo',
    'delivery_fee' => (int) ($_ENV['APP_DELIVERY_FEE'] ?? 500),
    'pagination' => 12,
    'ssl_ca_file' => trim($_ENV['SSL_CA_FILE'] ?? ''),
];
