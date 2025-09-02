<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Services\AdminService;

Auth::requireAdmin();
Auth::verifyCsrfOrFail();
Response::text((new AdminService())->addLookup('category', $_POST['cat'] ?? ''));
