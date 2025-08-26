<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\CartService;

$user = Auth::requireUser();
Auth::verifyCsrfOrFail();
Response::text((new CartService())->setQty(
    (int) $user['id'],
    (int) ($_POST['c'] ?? 0),
    (int) ($_POST['q'] ?? 0)
));
