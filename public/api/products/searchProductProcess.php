<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Response;
use App\Services\ProductService;

$page = (int) ($_POST['pg'] ?? 0);
$term = $_POST['p'] ?? '';
Response::html((new ProductService())->renderList($page, 'search', ['term' => $term]));
