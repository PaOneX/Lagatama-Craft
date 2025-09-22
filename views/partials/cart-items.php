<div class="lc-page-header">
    <h1 class="lc-page-title">Shopping Cart</h1>
    <p class="lc-page-subtitle"><?= $num ?> item<?= $num !== 1 ? 's' : '' ?> in your cart</p>
</div>
<?php foreach ($items as $d):
    $total = (float) $d['price'] * (int) $d['cart_qty'];
    $netTotal += $total;
?>
<div class="lc-cart-item">
    <img src="/<?= htmlspecialchars(ltrim($d['path'], '/')) ?>" alt="<?= htmlspecialchars($d['name']) ?>">
    <div class="lc-cart-item-info">
        <h4><?= htmlspecialchars($d['name']) ?></h4>
        <div class="lc-cart-item-meta">
            <span><?= htmlspecialchars($d['color_name']) ?></span>
            <span>Size: <?= htmlspecialchars($d['size_name']) ?></span>
            <span>Rs <?= number_format((float) $d['price'], 2) ?> each</span>
        </div>
    </div>
    <div class="lc-cart-qty">
        <button type="button" onclick="decrementCartQty('<?= (int) $d['cart_id'] ?>');" aria-label="Decrease">−</button>
        <input type="number" id="qty<?= (int) $d['cart_id'] ?>" value="<?= (int) $d['cart_qty'] ?>" disabled>
        <button type="button" onclick="incrementCartQty('<?= (int) $d['cart_id'] ?>');" aria-label="Increase">+</button>
    </div>
    <div class="lc-cart-item-right">
        <div class="lc-cart-item-price">Rs <?= number_format($total, 2) ?></div>
        <button type="button" class="lc-btn lc-btn-ghost" onclick="removeCart('<?= (int) $d['cart_id'] ?>')">
            <i class="bi bi-trash"></i> Remove
        </button>
    </div>
</div>
<?php endforeach; ?>

<div class="lc-cart-summary">
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
        <i class="bi bi-credit-card"></i> Proceed to Checkout
    </button>
</div>
