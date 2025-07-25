<?php



require_once __DIR__ . '/init.php';



use App\Core\Auth;

use App\Models\Landing;

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

$productId = (int) $d['id'];

$images = Product::imagesForProduct($productId, (string) ($d['path'] ?? ''));

$sizeVariants = Product::findSizeVariantsByStockId($stockId);

$colorVariants = Product::findColorVariantsByStockId($stockId);



if (empty($sizeVariants)) {

    $sizeVariants = [[

        'stock_id' => (int) $d['stock_id'],

        'price' => (float) $d['price'],

        'qty' => (int) $d['qty'],

        'size_id' => (int) $d['size_id'],

        'size_name' => (string) $d['size_name'],

    ]];

}



$hasAnyStock = false;

$defaultStockId = $stockId;

foreach ($sizeVariants as $variant) {

    if ((int) $variant['qty'] > 0) {

        $hasAnyStock = true;

    }

}

if ((int) $d['qty'] <= 0) {

    foreach ($sizeVariants as $variant) {

        if ((int) $variant['qty'] > 0) {

            $defaultStockId = (int) $variant['stock_id'];

            break;

        }

    }

}



$selectedVariant = $sizeVariants[0];

foreach ($sizeVariants as $variant) {

    if ((int) $variant['stock_id'] === $defaultStockId) {

        $selectedVariant = $variant;

        break;

    }

}



$deliveryFee = (int) config('app.delivery_fee', 500);

$unitPrice = (float) $selectedVariant['price'];

$selectedQty = (int) $selectedVariant['qty'];

$selectedInStock = $selectedQty > 0;

$inStock = $selectedInStock;



$priceRange = Product::linePriceRange((string) $d['name'], (int) $d['brand_id'], (int) $d['category_id']);

$listPrice = $priceRange['max'] > $unitPrice ? $priceRange['max'] : null;

$discountPct = $listPrice !== null && $listPrice > 0

    ? (int) round((($listPrice - $unitPrice) / $listPrice) * 100)

    : 0;



$soldCount = Product::soldCountForLine((string) $d['name'], (int) $d['brand_id'], (int) $d['category_id']);

$displayRating = $soldCount > 0 ? min(5.0, 4.0 + min(0.9, $soldCount / 200)) : null;



$deliveryFrom = (new DateTime('+3 days'))->format('M d');

$deliveryTo = (new DateTime('+10 days'))->format('M d');

$promoOffer = Landing::activeOffers()[0] ?? null;



$variantsJson = json_encode(array_map(static fn ($v) => [

    'stock_id' => (int) $v['stock_id'],

    'size_name' => $v['size_name'],

    'qty' => (int) $v['qty'],

    'price' => (float) $v['price'],

], $sizeVariants), JSON_HEX_TAG | JSON_HEX_AMP);



$shareUrl = config('app.url') . '/singleProductView.php?s=' . $defaultStockId;



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

            <?php if (count($images) > 1): ?>

            <div class="lc-gallery-thumbs lc-gallery-thumbs--vertical" role="tablist" aria-label="Product images">

                <?php foreach ($images as $i => $imgPath): ?>

                    <button type="button"

                            class="lc-gallery-thumb<?= $i === 0 ? ' active' : '' ?>"

                            data-src="/<?= htmlspecialchars(ltrim($imgPath, '/')) ?>"

                            role="tab"

                            aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"

                            aria-label="View image <?= $i + 1 ?>">

                        <img src="/<?= htmlspecialchars(ltrim($imgPath, '/')) ?>" alt="">

                    </button>

                <?php endforeach; ?>

            </div>

            <?php endif; ?>

            <div class="lc-gallery-main">

                <img id="lcGalleryMain"

                     src="/<?= htmlspecialchars(ltrim($images[0], '/')) ?>"

                     alt="<?= htmlspecialchars($d['name']) ?>">

            </div>

        </div>

        <div class="lc-product-detail-body">

        <div class="lc-product-detail-info">

            <div class="lc-product-meta-tags">

                <span class="lc-meta-tag"><?= htmlspecialchars($d['cat_name']) ?></span>

                <span class="lc-meta-tag"><?= htmlspecialchars($d['brand_name']) ?></span>

                <?php if ($discountPct > 0): ?>

                    <span class="lc-meta-tag lc-meta-tag--deal">Best price</span>

                <?php endif; ?>

            </div>



            <h1><?= htmlspecialchars($d['name']) ?></h1>



            <div class="lc-product-social">

                <?php if ($displayRating !== null): ?>

                <div class="lc-rating" aria-label="Rating <?= number_format($displayRating, 1) ?> out of 5">

                    <span class="lc-rating-stars">

                        <?php for ($s = 1; $s <= 5; $s++): ?>

                            <i class="bi bi-star<?= $displayRating >= $s ? '-fill' : ($displayRating >= $s - 0.5 ? '-half' : '') ?>"></i>

                        <?php endfor; ?>

                    </span>

                    <span class="lc-rating-value"><?= number_format($displayRating, 1) ?></span>

                </div>

                <?php endif; ?>

                <?php if ($soldCount > 0): ?>

                    <span class="lc-social-divider">|</span>

                    <span class="lc-sold-count"><?= number_format($soldCount) ?> sold</span>

                <?php else: ?>

                    <span class="lc-new-badge"><i class="bi bi-stars"></i> New arrival</span>

                <?php endif; ?>

            </div>



            <div class="lc-price-block">

                <div class="lc-price-row">

                    <span class="lc-price-current" id="lcProductPrice">Rs <?= number_format($unitPrice, 2) ?></span>

                    <?php if ($listPrice !== null): ?>

                        <span class="lc-price-discount" id="lcPriceDiscount"><?= $discountPct ?>% off</span>

                        <span class="lc-price-was" id="lcPriceWas">Rs <?= number_format($listPrice, 2) ?></span>

                    <?php endif; ?>

                </div>

                <p class="lc-price-note" id="lcPriceNote">

                    Delivery from Rs <?= number_format($deliveryFee, 2) ?> · Total from Rs <?= number_format($unitPrice + $deliveryFee, 2) ?>

                </p>

            </div>



            <?php if ($promoOffer): ?>

            <a href="<?= htmlspecialchars($promoOffer['link_url'] ?: 'home.php') ?>" class="lc-promo-banner">

                <i class="bi bi-tag-fill"></i>

                <span>

                    <strong><?= htmlspecialchars($promoOffer['title']) ?></strong>

                    <?php if (!empty($promoOffer['subtitle'])): ?>

                        — <?= htmlspecialchars($promoOffer['subtitle']) ?>

                    <?php endif; ?>

                </span>

                <i class="bi bi-chevron-right"></i>

            </a>

            <?php endif; ?>



            <script type="application/json" id="lcSizeVariants"><?= $variantsJson ?></script>

            <script type="application/json" id="lcPriceMeta"><?= json_encode([

                'list_price' => $listPrice,

                'delivery_fee' => $deliveryFee,

            ], JSON_HEX_TAG | JSON_HEX_AMP) ?></script>

            <input type="hidden" id="selectedStockId" value="<?= (int) $defaultStockId ?>">



            <?php if (count($colorVariants) > 1): ?>

            <div class="lc-variant-picker" id="lcColorPicker">

                <label class="lc-variant-picker-label">

                    Color: <span id="lcSelectedColor"><?= htmlspecialchars($d['color_name']) ?></span>

                </label>

                <div class="lc-color-options" role="group" aria-label="Available colors">

                    <?php foreach ($colorVariants as $cv):

                        $cvStockId = (int) $cv['stock_id'];

                        $cvQty = (int) $cv['qty'];

                        $isCurrentColor = $cvStockId === $stockId;

                    ?>

                    <a href="singleProductView.php?s=<?= $cvStockId ?>"

                       class="lc-color-option<?= $isCurrentColor ? ' active' : '' ?><?= $cvQty <= 0 ? ' unavailable' : '' ?>"

                       title="<?= htmlspecialchars($cv['color_name']) ?><?= $cvQty <= 0 ? ' (out of stock)' : '' ?>"

                       aria-label="<?= htmlspecialchars($cv['color_name']) ?>"

                       <?= $isCurrentColor ? 'aria-current="true"' : '' ?>>

                        <img src="/<?= htmlspecialchars(ltrim((string) $cv['path'], '/')) ?>"

                             alt="<?= htmlspecialchars($cv['color_name']) ?>">

                        <span class="lc-color-option-name"><?= htmlspecialchars($cv['color_name']) ?></span>

                    </a>

                    <?php endforeach; ?>

                </div>

            </div>

            <?php endif; ?>



            <div class="lc-variant-picker" id="lcSizePicker" data-delivery-fee="<?= $deliveryFee ?>">

                <label class="lc-variant-picker-label">

                    Size: <span id="lcSelectedSize"><?= htmlspecialchars($selectedVariant['size_name']) ?></span>

                </label>

                <div class="lc-size-options" role="group" aria-label="Available sizes">

                    <?php foreach ($sizeVariants as $variant):

                        $variantStockId = (int) $variant['stock_id'];

                        $variantQty = (int) $variant['qty'];

                        $isActive = $variantStockId === $defaultStockId;

                        $isAvailable = $variantQty > 0;

                    ?>

                    <button type="button"

                            class="lc-size-option<?= $isActive ? ' active' : '' ?><?= !$isAvailable ? ' unavailable' : '' ?>"

                            data-stock-id="<?= $variantStockId ?>"

                            title="<?= $isAvailable ? $variantQty . ' in stock' : 'Out of stock' ?>"

                            <?= !$isAvailable ? 'disabled' : '' ?>>

                        <?= htmlspecialchars($variant['size_name']) ?>

                    </button>

                    <?php endforeach; ?>

                </div>

            </div>



            <div class="lc-product-desc-block">

                <p class="lc-desc" id="lcProductDesc"><?= nl2br(htmlspecialchars($d['description'])) ?></p>

            </div>

            <div class="lc-detail-quick-specs">
                <div class="lc-quick-spec">
                    <span class="lc-quick-spec-label">Brand</span>
                    <span><?= htmlspecialchars($d['brand_name']) ?></span>
                </div>
                <div class="lc-quick-spec">
                    <span class="lc-quick-spec-label">Color</span>
                    <span><?= htmlspecialchars($d['color_name']) ?></span>
                </div>
                <div class="lc-quick-spec">
                    <span class="lc-quick-spec-label">Category</span>
                    <span><?= htmlspecialchars($d['cat_name']) ?></span>
                </div>
                <div class="lc-quick-spec">
                    <span class="lc-quick-spec-label">SKU</span>
                    <span id="lcQuickSku">LC-<?= str_pad((string) $defaultStockId, 5, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>

            <div class="lc-product-highlights lc-product-highlights--inline">
                <h3>Highlights</h3>
                <ul>
                    <li><i class="bi bi-check2"></i> Handcrafted by Sri Lankan artisans</li>
                    <li><i class="bi bi-check2"></i> <?= htmlspecialchars($d['brand_name']) ?> quality materials</li>
                    <li><i class="bi bi-check2"></i> Secure checkout via PayHere</li>
                    <li><i class="bi bi-check2"></i> Island-wide delivery available</li>
                </ul>
            </div>

        </div>

        <aside class="lc-product-sidebar">

            <div class="lc-sidebar-card lc-sidebar-unified">

                <div class="lc-seller-info">

                    <i class="bi bi-shop"></i>

                    <div>

                        <span class="lc-seller-label">Sold by</span>

                        <strong><?= htmlspecialchars($d['brand_name']) ?></strong>

                    </div>

                </div>



                <ul class="lc-service-list">

                    <li>

                        <i class="bi bi-truck"></i>

                        <div>

                            <strong>Rs <?= number_format($deliveryFee, 2) ?> delivery</strong>

                            <span>Estimated <?= $deliveryFrom ?> – <?= $deliveryTo ?></span>

                        </div>

                    </li>

                    <li>

                        <i class="bi bi-arrow-repeat"></i>

                        <div>

                            <strong>Return &amp; refund</strong>

                            <span>7-day return on unused items</span>

                        </div>

                    </li>

                    <li>

                        <i class="bi bi-shield-check"></i>

                        <div>

                            <strong>Secure checkout</strong>

                            <span>Safe payments via PayHere</span>

                        </div>

                    </li>

                </ul>

                <div class="lc-sidebar-divider"></div>

                <div class="lc-stock-badge<?= $inStock ? '' : ' out' ?>" id="lcStockBadge">

                    <i class="bi bi-<?= $inStock ? 'check-circle' : 'x-circle' ?>"></i>

                    <?php if ($inStock): ?>

                        In stock

                    <?php elseif ($hasAnyStock): ?>

                        Select an available size

                    <?php else: ?>

                        Out of stock

                    <?php endif; ?>

                </div>



                <p class="lc-stock-urgency<?= $selectedQty > 0 && $selectedQty <= 10 ? '' : ' d-none' ?>" id="lcStockUrgency">

                    Only <span id="lcStockUrgencyQty"><?= $selectedQty ?></span> left

                </p>



                <div id="lcPurchaseBlock" class="<?= $hasAnyStock ? '' : 'd-none' ?>">

                    <div class="lc-qty-stepper">

                        <label for="qty">Quantity</label>

                        <div class="lc-qty-controls">

                            <button type="button" class="lc-qty-btn" id="lcQtyMinus" aria-label="Decrease quantity">−</button>

                            <input value="1" type="number" class="form-control" id="qty" min="1" max="<?= max(1, $selectedQty) ?>" readonly>

                            <button type="button" class="lc-qty-btn" id="lcQtyPlus" aria-label="Increase quantity">+</button>

                        </div>

                    </div>



                    <div class="lc-sidebar-actions">

                        <?php if ($loggedIn): ?>

                            <button type="button" class="lc-btn lc-btn-primary lc-btn-buy" id="lcBuyNowBtn" onclick="buyNow();" <?= !$selectedInStock ? 'disabled' : '' ?>>

                                Buy Now

                            </button>

                            <button type="button" class="lc-btn lc-btn-outline lc-btn-cart" id="lcAddCartBtn" onclick="addtoCart();" <?= !$selectedInStock ? 'disabled' : '' ?>>

                                <i class="bi bi-cart-plus"></i> Add to Cart

                            </button>

                        <?php else: ?>

                            <button type="button" class="lc-btn lc-btn-primary lc-btn-buy" onclick="alert5();">Buy Now</button>

                            <button type="button" class="lc-btn lc-btn-outline lc-btn-cart" onclick="alert5();">

                                <i class="bi bi-cart-plus"></i> Add to Cart

                            </button>

                        <?php endif; ?>

                    </div>



                    <div class="lc-secondary-actions">

                        <button type="button" class="lc-secondary-btn" id="lcShareBtn" data-share-url="<?= htmlspecialchars($shareUrl) ?>">

                            <i class="bi bi-share"></i> Share

                        </button>

                        <button type="button" class="lc-secondary-btn" id="lcWishlistBtn" data-stock-id="<?= (int) $defaultStockId ?>">

                            <i class="bi bi-heart"></i> <span id="lcWishlistLabel">Save</span>

                        </button>

                    </div>

                </div>



                <?php if (!$hasAnyStock): ?>

                <p class="lc-out-of-stock-note">This item is out of stock in all sizes. Check back later.</p>

                <?php endif; ?>



                <?php if ($loggedIn): ?>

                <button type="button" class="lc-shipping-link" onclick="shipAdd();">

                    <i class="bi bi-geo-alt"></i> Manage shipping address

                </button>

                <?php endif; ?>

            </div>

        </aside>

        </div>

    </div>



    <?php if ($loggedIn): ?>

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



    <div class="lc-product-specs">

        <h2 class="lc-specs-title">Product Details</h2>

        <dl class="lc-specs-grid">

            <div class="lc-spec-row">

                <dt>SKU</dt>

                <dd id="lcSpecSku">LC-<?= str_pad((string) $defaultStockId, 5, '0', STR_PAD_LEFT) ?></dd>

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

                <dd id="lcSpecSize"><?= htmlspecialchars($selectedVariant['size_name']) ?></dd>

            </div>

            <div class="lc-spec-row">

                <dt>Unit Price</dt>

                <dd id="lcSpecPrice">Rs <?= number_format($unitPrice, 2) ?></dd>

            </div>

            <div class="lc-spec-row">

                <dt>Delivery</dt>

                <dd>Island-wide · Rs <?= number_format($deliveryFee, 2) ?></dd>

            </div>

            <div class="lc-spec-row">

                <dt>Availability</dt>

                <dd id="lcSpecAvail"><?= $selectedInStock ? (int) $selectedVariant['qty'] . ' units in stock (' . htmlspecialchars($selectedVariant['size_name']) . ')' : 'Currently unavailable' ?></dd>

            </div>

            <div class="lc-spec-row">

                <dt>Images</dt>

                <dd><?= count($images) ?> photo<?= count($images) !== 1 ? 's' : '' ?></dd>

            </div>

        </dl>



        <div class="lc-size-availability">

            <h3 class="lc-size-availability-title">All sizes &amp; stock</h3>

            <table class="lc-size-table">

                <thead>

                    <tr>

                        <th>Size</th>

                        <th>Price</th>

                        <th>In stock</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($sizeVariants as $variant):

                    $variantQty = (int) $variant['qty'];

                    $isActive = (int) $variant['stock_id'] === $defaultStockId;

                ?>

                    <tr class="<?= $isActive ? 'selected' : '' ?>" data-stock-id="<?= (int) $variant['stock_id'] ?>">

                        <td><?= htmlspecialchars($variant['size_name']) ?></td>

                        <td>Rs <?= number_format((float) $variant['price'], 2) ?></td>

                        <td><?= $variantQty ?></td>

                        <td>

                            <?php if ($variantQty > 0): ?>

                                <span class="lc-avail-badge in">Available</span>

                            <?php else: ?>

                                <span class="lc-avail-badge out">Out of stock</span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>



        <div class="lc-product-highlights">

            <h3>Highlights</h3>

            <ul>

                <li><i class="bi bi-check2"></i> Handcrafted by Sri Lankan artisans</li>

                <li><i class="bi bi-check2"></i> <?= htmlspecialchars($d['brand_name']) ?> quality materials</li>

                <li><i class="bi bi-check2"></i> Secure checkout via PayHere</li>

                <li><i class="bi bi-check2"></i> <?= htmlspecialchars($d['cat_name']) ?> · Available in multiple sizes</li>

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

