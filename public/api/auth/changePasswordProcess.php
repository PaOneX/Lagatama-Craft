<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\AuthService;

$user = Auth::requireUser();
Auth::verifyCsrfOrFail();
$result = (new AuthService())->changePassword(
    (int) $user['id'],
    $_POST['op1'] ?? '',
    $_POST['n1'] ?? '',
    $_POST['n1'] ?? ''
);
Response::text($result === 'success' ? 'Success' : $result);
