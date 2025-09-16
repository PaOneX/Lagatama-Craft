<?php

require_once dirname(__DIR__) . '/init.php';

$pageTitle = 'Product Management';
$activeNav = 'products';

admin_layout($pageTitle, $activeNav, function () {
    ?>
    <div class="admin-page-intro">
        <p>Manage product attributes — brands, categories, sizes, and colors. Register actual products under <strong>Stock</strong>.</p>
    </div>
    <div class="admin-form-grid">
        <div class="admin-form-card">
            <div class="admin-form-card-icon"><i class="bi bi-bookmark"></i></div>
            <label for="bName">Brand Name</label>
            <input type="text" class="form-control mt-1 mb-3" id="bName" placeholder="e.g. Artisan Co.">
            <button class="admin-btn admin-btn-primary" type="button" onclick="addBrand();">Add Brand</button>
        </div>
        <div class="admin-form-card">
            <div class="admin-form-card-icon"><i class="bi bi-grid"></i></div>
            <label for="catName">Category</label>
            <input type="text" class="form-control mt-1 mb-3" id="catName" placeholder="e.g. Tote Bags">
            <button class="admin-btn admin-btn-primary" type="button" onclick="addCategory();">Add Category</button>
        </div>
        <div class="admin-form-card">
            <div class="admin-form-card-icon"><i class="bi bi-arrows-angle-expand"></i></div>
            <label for="size">Size</label>
            <input type="text" class="form-control mt-1 mb-3" id="size" placeholder="e.g. Medium">
            <button class="admin-btn admin-btn-primary" type="button" onclick="addSize();">Add Size</button>
        </div>
        <div class="admin-form-card">
            <div class="admin-form-card-icon"><i class="bi bi-palette"></i></div>
            <label for="clr">Color</label>
            <input type="text" class="form-control mt-1 mb-3" id="clr" placeholder="e.g. Natural">
            <button class="admin-btn admin-btn-primary" type="button" onclick="addColor();">Add Color</button>
        </div>
    </div>
    <?php
});
