<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Response;
use App\Services\ProductService;

$page = (int) ($_POST['pg'] ?? 0);
$filters = [
    'color_id' => (int) ($_POST['co'] ?? 0) ?: null,
    'category_id' => (int) ($_POST['cat'] ?? 0) ?: null,
    'brand_id' => (int) ($_POST['b'] ?? 0) ?: null,
    'size_id' => (int) ($_POST['s'] ?? 0) ?: null,
    'min_price' => $_POST['min'] ?? '',
    'max_price' => $_POST['max'] ?? '',
];
Response::html((new ProductService())->renderList($page, 'advanced', $filters));
