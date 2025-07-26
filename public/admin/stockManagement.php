<?php

require_once dirname(__DIR__) . '/init.php';

use App\Core\Database;

$pageTitle = 'Stock Management';
$activeNav = 'stock';

admin_layout($pageTitle, $activeNav, function () {
    ?>
    <div id="reg">
        <div class="admin-tab-switch">
            <button type="button" class="active">Product Registration</button>
            <button type="button" onclick="changeStockView();">Stock Update</button>
        </div>

        <div class="admin-card" style="max-width: 640px; margin: 0 auto;">
            <h3 class="admin-card-title mb-4">Register New Product</h3>
            <div class="mb-3">
                <label class="form-label" for="pname">Product Name</label>
                <input type="text" class="form-control" id="pname" placeholder="Enter product name">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="brand">Brand</label>
                    <select class="form-select" id="brand">
                        <option value="0">Select Brand</option>
                        <?php foreach (Database::fetchAll('SELECT * FROM `brand`') as $d): ?>
                            <option value="<?= (int) $d['brand_id'] ?>"><?= htmlspecialchars($d['brand_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="cat">Category</label>
                    <select class="form-select" id="cat">
                        <option value="0">Select Category</option>
                        <?php foreach (Database::fetchAll('SELECT * FROM `category`') as $d2): ?>
                            <option value="<?= (int) $d2['cat_id'] ?>"><?= htmlspecialchars($d2['cat_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Colors</label>
                <small class="text-muted d-block mb-2">Add every color you sell for this product.</small>
                <div id="colorRows"
                     class="admin-size-rows"
                     data-color-options="<?= htmlspecialchars(json_encode(array_map(static fn ($row) => [
                         'id' => (int) $row['color_id'],
                         'name' => $row['color_name'],
                     ], Database::fetchAll('SELECT * FROM `color` ORDER BY `color_name`'))), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <button type="button" class="admin-btn admin-btn-outline admin-size-add-btn" onclick="addColorRow();">
                    <i class="bi bi-plus-lg"></i> Add color
                </button>
            </div>
            <div class="mb-3">
                <label class="form-label">Sizes &amp; initial stock</label>
                <small class="text-muted d-block mb-2">Add every size you sell. You can set a starting quantity and price now, or add stock later.</small>
                <div id="sizeRows"
                     class="admin-size-rows"
                     data-size-options="<?= htmlspecialchars(json_encode(array_map(static fn ($row) => [
                         'id' => (int) $row['size_id'],
                         'name' => $row['size_name'],
                     ], Database::fetchAll('SELECT * FROM `size` ORDER BY `size_name`'))), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <button type="button" class="admin-btn admin-btn-outline admin-size-add-btn" onclick="addSizeRow();">
                    <i class="bi bi-plus-lg"></i> Add size
                </button>
            </div>
            <div class="mb-3">
                <label class="form-label" for="desc">Description</label>
                <textarea id="desc" class="form-control" rows="4" placeholder="Product description"></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label" for="file">Product Images</label>
                <input id="file" class="form-control" type="file" accept="image/*" multiple>
                <small class="text-muted">Select one or more images. The first image is the main photo.</small>
            </div>
            <button class="admin-btn admin-btn-primary" onclick="regProduct();">Register Product</button>
        </div>
    </div>

    <div class="d-none" id="update">
        <div class="admin-tab-switch">
            <button type="button" onclick="changeStockView();">Product Registration</button>
            <button type="button" class="active">Stock Update</button>
        </div>

        <div class="admin-card" style="max-width: 480px; margin: 0 auto;">
            <h3 class="admin-card-title mb-4">Update Stock</h3>
            <div class="mb-3">
                <label class="form-label" for="productSelct">Product</label>
                <select class="form-select" id="productSelct">
                    <option value="0">Select product</option>
                    <?php foreach (Database::fetchAll(
                        'SELECT p.id, p.name, cl.color_name, sz.size_name
                         FROM `product` p
                         INNER JOIN `color` cl ON p.color_id = cl.color_id
                         INNER JOIN `size` sz ON p.size_id = sz.size_id
                         ORDER BY p.name, cl.color_name, sz.size_name'
                    ) as $d): ?>
                        <option value="<?= (int) $d['id'] ?>"><?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['color_name']) ?> — <?= htmlspecialchars($d['size_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="qty">Quantity</label>
                <input type="number" class="form-control" id="qty" min="1" placeholder="Enter quantity">
            </div>
            <div class="mb-4">
                <label class="form-label" for="price">Price (LKR)</label>
                <input type="number" class="form-control" id="price" min="0" step="0.01" placeholder="Enter price">
            </div>
            <button class="admin-btn admin-btn-primary" onclick="updateStock();">Update Stock</button>
        </div>
    </div>
    <?php
});
