<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;

$user = Auth::requireUser();
$payment = json_decode($_POST['payment'] ?? '{}', true);
$ohId = (int) ($payment['oh_id'] ?? 0);
Response::json(['resp' => 'Success', 'order_id' => $ohId]);
