<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;
use App\Models\Product;

$stockId = (int) ($_GET['s'] ?? 0);
if ($stockId <= 0) {
    header('Location: home.php');
    exit;
}

$d = Product::findDetailByStockId($stockId);
if ($d === null) {
    header('Location: home.php');
    exit;
}

$loggedIn = Auth::checkUser();
$inStock = (int) $d['qty'] > 0;
$productId = (int) $d['id'];
$images = Product::imagesForProduct($productId, (string) ($d['path'] ?? ''));
$deliveryFee = 500;
$unitPrice = (float) $d['price'];

ob_start();
include base_path('views/partials/header.php');
include base_path('views/partials/navbar.php');
?>
<div class="lc-page">
    <a href="home.php" class="lc-back-link">
        <i class="bi bi-arrow-left"></i> Back to Shop
    </a>

    <div class="lc-product-detail">
        <div class="lc-product-gallery">
            <div class="lc-gallery-main">
                <img id="lcGalleryMain"
                     src="/<?= htmlspecialchars(ltrim($images[0], '/')) ?>"
                     alt="<?= htmlspecialchars($d['name']) ?>">
            </div>
            <?php if (count($images) > 1): ?>
            <div class="lc-gallery-thumbs">
                <?php foreach ($images as $i => $imgPath): ?>
                    <button type="button"
                            class="lc-gallery-thumb<?= $i === 0 ? ' active' : '' ?>"
                            data-src="/<?= htmlspecialchars(ltrim($imgPath, '/')) ?>"
                            aria-label="View image <?= $i + 1 ?>">
                        <img src="/<?= htmlspecialchars(ltrim($imgPath, '/')) ?>" alt="">
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="lc-product-detail-info">
            <div class="lc-product-meta-tags">
                <span class="lc-meta-tag"><?= htmlspecialchars($d['cat_name']) ?></span>
                <span class="lc-meta-tag"><?= htmlspecialchars($d['brand_name']) ?></span>
            </div>

            <h1><?= htmlspecialchars($d['name']) ?></h1>
            <p class="lc-desc"><?= nl2br(htmlspecialchars($d['description'])) ?></p>

            <div class="lc-price-tag">Rs <?= number_format($unitPrice, 2) ?></div>
            <p class="lc-price-note">+ Rs <?= number_format($deliveryFee, 2) ?> delivery · Total from Rs <?= number_format($unitPrice + $deliveryFee, 2) ?></p>

            <div class="lc-stock-badge<?= $inStock ? '' : ' out' ?>">
                <i class="bi bi-<?= $inStock ? 'check-circle' : 'x-circle' ?>"></i>
                <?= $inStock ? 'In stock (' . (int) $d['qty'] . ' available)' : 'Out of stock' ?>
            </div>

            <?php if ($inStock): ?>
            <div class="lc-qty-row">
                <label for="qty">Quantity</label>
                <input value="1" type="number" class="form-control" id="qty" min="1" max="<?= (int) $d['qty'] ?>">
            </div>
            <div class="lc-detail-actions">
                <?php if ($loggedIn): ?>
                    <button type="button" class="lc-btn lc-btn-outline" onclick="addtoCart('<?= (int) $d['stock_id'] ?>');">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                    <button type="button" class="lc-btn lc-btn-primary" onclick="buyNow('<?= (int) $d['stock_id'] ?>');">
                        <i class="bi bi-lightning"></i> Buy Now
                    </button>
                <?php else: ?>
                    <button type="button" class="lc-btn lc-btn-outline" onclick="alert5();">Add to Cart</button>
                    <button type="button" class="lc-btn lc-btn-primary" onclick="alert5();">Buy Now</button>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($loggedIn): ?>
            <span class="lc-shipping-link" onclick="shipAdd();">
                <i class="bi bi-geo-alt"></i> Manage shipping address
            </span>
            <div class="modal fade" id="staticBackdrop" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Shipping Address</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label" for="no1">No</label>
                                    <input type="text" class="form-control" id="no1">
                                </div>
                                <div class="col-8">
                                    <label class="form-label" for="line1">Address line 1</label>
                                    <input type="text" class="form-control" id="line1">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="line2">Address line 2</label>
                                    <input type="text" class="form-control" id="line2">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="lc-btn lc-btn-primary" onclick="setAddress();">Save Address</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="lc-product-specs">
        <h2 class="lc-specs-title">Product Details</h2>
        <dl class="lc-specs-grid">
            <div class="lc-spec-row">
                <dt>SKU</dt>
                <dd>LC-<?= str_pad((string) $d['stock_id'], 5, '0', STR_PAD_LEFT) ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Brand</dt>
                <dd><?= htmlspecialchars($d['brand_name']) ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Category</dt>
                <dd><?= htmlspecialchars($d['cat_name']) ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Color</dt>
                <dd><?= htmlspecialchars($d['color_name']) ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Size</dt>
                <dd><?= htmlspecialchars($d['size_name']) ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Unit Price</dt>
                <dd>Rs <?= number_format($unitPrice, 2) ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Delivery</dt>
                <dd>Island-wide · Rs <?= number_format($deliveryFee, 2) ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Availability</dt>
                <dd><?= $inStock ? (int) $d['qty'] . ' units in stock' : 'Currently unavailable' ?></dd>
            </div>
            <div class="lc-spec-row">
                <dt>Images</dt>
                <dd><?= count($images) ?> photo<?= count($images) !== 1 ? 's' : '' ?></dd>
            </div>
        </dl>

        <div class="lc-product-highlights">
            <h3>Highlights</h3>
            <ul>
                <li><i class="bi bi-check2"></i> Handcrafted by Sri Lankan artisans</li>
                <li><i class="bi bi-check2"></i> <?= htmlspecialchars($d['brand_name']) ?> quality materials</li>
                <li><i class="bi bi-check2"></i> Secure checkout via PayHere</li>
                <li><i class="bi bi-check2"></i> <?= htmlspecialchars($d['cat_name']) ?> · <?= htmlspecialchars($d['size_name']) ?> size</li>
            </ul>
        </div>
    </div>
</div>
<?php include base_path('views/partials/footer.php'); ?>
<script src="https://www.payhere.lk/lib/payhere.js"></script>
<?php
$content = ob_get_clean();
$pageScripts = [
    'assets/js/shop/auth.js',
    'assets/js/shop/cart.js',
    'assets/js/shop/checkout.js',
    'assets/js/shop/product-detail.js',
];
include base_path('views/layouts/customer.php');
