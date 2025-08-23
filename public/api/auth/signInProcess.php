<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Response;
use App\Services\AuthService;

$remember = ($_POST['r'] ?? '') === 'true' || ($_POST['r'] ?? '') === '1';
Response::text((new AuthService())->signIn($_POST['e'] ?? '', $_POST['pw'] ?? '', $remember));
