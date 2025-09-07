<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\AdminService;

Auth::requireAdmin();
Auth::verifyCsrfOrFail();
Response::text((new AdminService())->updateStock(
    (int) ($_POST['sp'] ?? 0),
    (float) ($_POST['p'] ?? 0),
    (int) ($_POST['q'] ?? 0)
));
