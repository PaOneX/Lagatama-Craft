<?php

require_once __DIR__ . '/init.php';

$title = 'About Us | Lagatama Craft';

ob_start();
include base_path('views/partials/header.php');
include base_path('views/partials/navbar.php');
?>
<div class="lc-page">
    <div class="lc-page-header text-center">
        <img src="<?= resource_url('images/hansi logo jpg.jpg') ?>" alt="Lagatama Craft" style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin-bottom:16px;">
        <h1 class="lc-page-title">About Lagatama Craft</h1>
        <p class="lc-page-subtitle">Handcrafted bags and accessories from Sri Lankan artisans</p>
    </div>

    <div class="lc-about-section">
        <h2><i class="bi bi-eye me-2"></i>Our Vision</h2>
        <p>We craft quality bags and accessories with care, bringing unique designs from Sri Lankan artisans to customers who value handmade quality.</p>
    </div>
    <div class="lc-about-section">
        <h2><i class="bi bi-bullseye me-2"></i>Our Mission</h2>
        <p>To deliver durable, stylish craft products while supporting local makers and providing excellent customer service across Sri Lanka.</p>
    </div>
    <div class="lc-about-section">
        <h2><i class="bi bi-heart me-2"></i>Our Service</h2>
        <p>Secure online ordering, island-wide delivery, and responsive support for every purchase you make with Lagatama Craft.</p>
    </div>
</div>
<?php include base_path('views/partials/footer.php'); ?>
<?php
$content = ob_get_clean();
$pageScripts = ['assets/js/shop/auth.js'];
include base_path('views/layouts/customer.php');
