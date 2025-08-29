<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\CheckoutService;

$user = Auth::requireUser();
$ohId = (int) ($_POST['oh_id'] ?? 0);
$status = (new CheckoutService())->getOrderStatus($ohId, (int) $user['id']);
Response::text($status ?? 'unknown');
