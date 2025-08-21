<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;

Auth::requireAdmin();
Auth::verifyCsrfOrFail();
Auth::logoutAdmin();
Response::text('success');
