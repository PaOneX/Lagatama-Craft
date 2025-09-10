<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;
use App\Models\Lookup;
use App\Models\Landing;

if (!Auth::checkUser()) {
    header('Location: index.php');
    exit;
}

$title = 'Shop | Lagatama Craft';
$colors = Lookup::colors();
$categories = Lookup::categories();
$brands = Lookup::brands();
$sizes = Lookup::sizes();
$landingSettings = Landing::settings();
$landingOffers = Landing::activeOffers();

ob_start();
include base_path('views/partials/header.php');
include base_path('views/partials/navbar.php');
?>
<div class="lc-page">
    <?php
    $settings = $landingSettings;
    $offers = $landingOffers;
    include base_path('views/partials/landing-offers.php');
    ?>

    <div class="lc-toolbar">
        <div class="lc-search-wrap">
            <i class="bi bi-search"></i>
            <input class="form-control" type="search" placeholder="Search products..." id="sProduct" autocomplete="off">
        </div>
        <button class="lc-btn lc-btn-outline" type="button" id="filterToggle" onclick="advSearch();">
            <i class="bi bi-funnel"></i> Filters
        </button>
    </div>

    <div class="d-none" id="filterId">
        <div class="lc-filter-panel">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <label class="form-label" for="color">Color</label>
                    <select class="form-select" id="color">
                        <option value="0">All colors</option>
                        <?php foreach ($colors as $c): ?>
                            <option value="<?= (int) $c['color_id'] ?>"><?= htmlspecialchars($c['color_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label" for="cat">Category</label>
                    <select class="form-select" id="cat">
                        <option value="0">All categories</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= (int) $c['cat_id'] ?>"><?= htmlspecialchars($c['cat_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label" for="brand">Brand</label>
                    <select class="form-select" id="brand">
                        <option value="0">All brands</option>
                        <?php foreach ($brands as $b): ?>
                            <option value="<?= (int) $b['brand_id'] ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label" for="size">Size</label>
                    <select class="form-select" id="size">
                        <option value="0">All sizes</option>
                        <?php foreach ($sizes as $s): ?>
                            <option value="<?= (int) $s['size_id'] ?>"><?= htmlspecialchars($s['size_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="min">Min price (Rs)</label>
                    <input type="number" class="form-control" placeholder="0" id="min" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="max">Max price (Rs)</label>
                    <input type="number" class="form-control" placeholder="Any" id="max" min="0">
                </div>
                <div class="col-12">
                    <button class="lc-btn lc-btn-primary" type="button" onclick="advSearchProduct(0);">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="pid"></div>
</div>
<?php include base_path('views/partials/footer.php'); ?>
<script>document.addEventListener('DOMContentLoaded', () => { initShop(); loadProduct(0); });</script>
<?php
$content = ob_get_clean();
$pageScripts = ['assets/js/shop/auth.js', 'assets/js/shop/cart.js', 'assets/js/shop/products.js'];
include base_path('views/layouts/customer.php');
