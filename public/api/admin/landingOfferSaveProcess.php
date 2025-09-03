<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\LandingService;

Auth::requireAdmin();
Auth::verifyCsrfOrFail();
Response::text((new LandingService())->saveOffer($_POST, $_FILES['offer_media'] ?? null));
