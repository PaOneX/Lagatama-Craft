<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Response;
use App\Services\AuthService;

Response::text((new AuthService())->adminSignIn($_POST['e'] ?? '', $_POST['pw'] ?? ''));
