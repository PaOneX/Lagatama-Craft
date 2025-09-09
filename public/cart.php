<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;

if (!Auth::checkUser()) {
    header('Location: index.php');
    exit;
}

ob_start();
include base_path('views/partials/header.php');
include base_path('views/partials/navbar.php');
?>
<div class="lc-page">
    <div id="cartBody"></div>
</div>
<?php include base_path('views/partials/footer.php'); ?>
<script src="https://www.payhere.lk/lib/payhere.js"></script>
<script>document.addEventListener('DOMContentLoaded', () => loadCart());</script>
<?php
$content = ob_get_clean();
$pageScripts = ['assets/js/shop/auth.js', 'assets/js/shop/cart.js', 'assets/js/shop/checkout.js'];
include base_path('views/layouts/customer.php');
