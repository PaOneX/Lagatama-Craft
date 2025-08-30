<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Response;
use App\Services\ProductService;

$page = (int) ($_POST['p'] ?? 0);
Response::html((new ProductService())->renderList($page, 'list'));
