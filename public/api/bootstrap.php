<?php

require_once dirname(__DIR__) . '/init.php';

use App\Core\Auth;

/**
 * @param 'admin'|'user'|null $requireAuth
 */
function api_guard(?string $requireAuth = null, bool $csrf = false): void
{
    if ($requireAuth === 'admin') {
        Auth::requireAdmin();
    } elseif ($requireAuth === 'user') {
        Auth::requireUser();
    }

    if ($csrf) {
        Auth::verifyCsrfOrFail();
    }
}
