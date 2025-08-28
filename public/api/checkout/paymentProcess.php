<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\PaymentService;

$user = Auth::requireUser();
Auth::verifyCsrfOrFail();

try {
    $service = new PaymentService();
    if (isset($_POST['cart']) && $_POST['cart'] === 'true') {
        Response::json($service->initiateFromCart((int) $user['id']));
    }
    Response::json($service->initiateBuyNow(
        (int) $user['id'],
        (int) ($_POST['stockId'] ?? 0),
        (int) ($_POST['qty'] ?? 0)
    ));
} catch (\Throwable $e) {
    Response::text($e->getMessage(), 400);
}
