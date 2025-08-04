<?php

return [
    'merchant_id' => $_ENV['PAYHERE_MERCHANT_ID'] ?? '1224940',
    'merchant_secret' => $_ENV['PAYHERE_MERCHANT_SECRET'] ?? '',
    'sandbox' => filter_var($_ENV['PAYHERE_SANDBOX'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'currency' => 'LKR',
];
