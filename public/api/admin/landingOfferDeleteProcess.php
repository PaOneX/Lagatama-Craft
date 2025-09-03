<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\LandingService;

Auth::requireAdmin();
Auth::verifyCsrfOrFail();

$id = (int) ($_POST['id'] ?? 0);
Response::text((new LandingService())->deleteOffer($id));
