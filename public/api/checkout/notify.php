<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Response;
use App\Services\PaymentService;

$result = (new PaymentService())->verifyNotify($_POST);
Response::text($result ? 'OK' : 'INVALID', $result ? 200 : 400);
