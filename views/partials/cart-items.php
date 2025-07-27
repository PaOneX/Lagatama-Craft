<div class="lc-page-header">
    <h1 class="lc-page-title">Checkout</h1>
    <p class="lc-page-subtitle">Review your items — sizes, quantities, and stock — before payment</p>
</div>
<?php foreach ($items as $d):
    $cartQty = (int) $d['cart_qty'];
    $stockQty = (int) $d['stock_qty'];
    $total = (float) $d['price'] * $cartQty;
    $netTotal += $total;
    $lowStock = $stockQty > 0 && $stockQty <= 5;
    $atMax = $cartQty >= $stockQty;
?>
<div class="lc-cart-item" data-cart-id="<?= (int) $d['cart_id'] ?>">
    <img src="/<?= htmlspecialchars(ltrim($d['path'], '/')) ?>" alt="<?= htmlspecialchars($d['name']) ?>">
    <div class="lc-cart-item-info">
        <h4><?= htmlspecialchars($d['name']) ?></h4>
        <div class="lc-cart-item-meta">
            <span><?= htmlspecialchars($d['brand_name']) ?></span>
            <span><?= htmlspecialchars($d['cat_name']) ?></span>
            <span><?= htmlspecialchars($d['color_name']) ?></span>
            <span>Size: <strong><?= htmlspecialchars($d['size_name']) ?></strong></span>
        </div>
        <div class="lc-cart-item-stock">
            <span>Rs <?= number_format((float) $d['price'], 2) ?> each</span>
            <span class="lc-stock-pill<?= $stockQty === 0 ? ' out' : ($lowStock ? ' low' : '') ?>">
                <?= $stockQty ?> available in this size
            </span>
            <?php if ($lowStock && $stockQty > 0): ?>
                <span class="lc-stock-warning">Only <?= $stockQty ?> left — order soon</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="lc-cart-qty">
        <button type="button" onclick="decrementCartQty('<?= (int) $d['cart_id'] ?>');" aria-label="Decrease">−</button>
        <input type="number"
               id="qty<?= (int) $d['cart_id'] ?>"
               value="<?= $cartQty ?>"
               min="1"
               max="<?= $stockQty ?>"
               data-max="<?= $stockQty ?>"
               disabled>
        <button type="button"
                onclick="incrementCartQty('<?= (int) $d['cart_id'] ?>');"
                aria-label="Increase"
                <?= $atMax ? 'disabled' : '' ?>>
            +
        </button>
    </div>
    <div class="lc-cart-item-right">
        <div class="lc-cart-item-price">Rs <?= number_format($total, 2) ?></div>
        <div class="lc-cart-line-detail"><?= $cartQty ?> × <?= htmlspecialchars($d['size_name']) ?></div>
        <button type="button" class="lc-btn lc-btn-ghost" onclick="removeCart('<?= (int) $d['cart_id'] ?>')">
            <i class="bi bi-trash"></i> Remove
        </button>
    </div>
</div>
<?php endforeach; ?>

<div class="lc-cart-summary">
    <h3 class="lc-checkout-review-title">Order summary</h3>
    <div class="lc-checkout-items-table">
        <div class="lc-checkout-items-head">
            <span>Item</span>
            <span>Size</span>
            <span>Qty</span>
            <span>Stock</span>
            <span class="text-end">Subtotal</span>
        </div>
        <?php foreach ($items as $d):
            $lineTotal = (float) $d['price'] * (int) $d['cart_qty'];
        ?>
        <div class="lc-checkout-items-row">
            <span class="lc-checkout-item-name"><?= htmlspecialchars($d['name']) ?></span>
            <span><?= htmlspecialchars($d['size_name']) ?></span>
            <span><?= (int) $d['cart_qty'] ?></span>
            <span><?= (int) $d['stock_qty'] ?></span>
            <span class="text-end">Rs <?= number_format($lineTotal, 2) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="lc-cart-summary-row">
        <span>Items (<?= $num ?>)</span>
        <span>Rs <?= number_format($netTotal, 2) ?></span>
    </div>
    <div class="lc-cart-summary-row">
        <span>Delivery fee</span>
        <span>Rs <?= number_format($deliveryFee, 2) ?></span>
    </div>
    <div class="lc-cart-summary-row total">
        <span>Total</span>
        <span>Rs <?= number_format($netTotal + $deliveryFee, 2) ?></span>
    </div>
    <button type="button" class="lc-btn lc-btn-primary w-100 mt-3" style="padding:14px;" onclick="checkOut();">
        <i class="bi bi-credit-card"></i> Proceed to Payment
    </button>
</div>
