<?php

return [
    'host' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
    'port' => (int) ($_ENV['MAIL_PORT'] ?? 465),
    'username' => $_ENV['MAIL_USERNAME'] ?? '',
    'password' => $_ENV['MAIL_PASSWORD'] ?? '',
    'from' => $_ENV['MAIL_FROM'] ?? '',
    'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'Lagatama Craft',
];
