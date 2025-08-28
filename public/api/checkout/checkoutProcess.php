<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;

$user = Auth::requireUser();
$ohId = (int) ($_POST['oh_id'] ?? json_decode($_POST['payment'] ?? '{}', true)['oh_id'] ?? 0);
Response::json(['resp' => 'Success', 'order_id' => $ohId]);
