<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\CartService;

$user = Auth::requireUser();
Auth::verifyCsrfOrFail();
Response::text((new CartService())->addItem(
    (int) $user['id'],
    (int) ($_POST['s'] ?? 0),
    (int) ($_POST['q'] ?? 0)
));
