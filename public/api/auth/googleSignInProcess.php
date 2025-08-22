<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Response;
use App\Services\AuthService;

$credential = $_POST['credential'] ?? '';

Response::text((new AuthService())->googleSignIn($credential));
