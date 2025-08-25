<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\CartService;

$user = Auth::requireUser();
Response::html((new CartService())->renderCart((int) $user['id']));
