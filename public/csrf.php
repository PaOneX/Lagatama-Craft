<?php

require_once __DIR__ . '/init.php';

use App\Core\Session;

header('Content-Type: application/javascript; charset=utf-8');
echo 'window.CSRF_TOKEN = ' . json_encode(Session::csrfToken()) . ';';
