<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\AdminService;

$user = Auth::requireUser();
Auth::verifyCsrfOrFail();
Response::text((new AdminService())->updateProfile((int) $user['id'], $_POST));
