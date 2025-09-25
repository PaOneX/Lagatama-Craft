<div class="lc-product-card">
    <a href="singleProductView.php?s=<?= (int) $d['stock_id'] ?>" class="lc-product-img-wrap">
        <img src="/<?= htmlspecialchars(ltrim($d['path'], '/')) ?>" alt="<?= htmlspecialchars($d['name']) ?>" loading="lazy">
    </a>
    <div class="lc-product-body">
        <h3 class="lc-product-name"><?= htmlspecialchars($d['name']) ?></h3>
        <p class="lc-product-desc"><?= htmlspecialchars($d['description']) ?></p>
        <p class="lc-product-price">Rs <?= number_format((float) $price, 2) ?></p>
        <div class="lc-product-actions">
            <a href="singleProductView.php?s=<?= (int) $d['stock_id'] ?>" class="lc-btn lc-btn-outline">View</a>
            <button type="button" class="lc-btn lc-btn-primary" onclick="quickAddToCart(<?= (int) $d['stock_id'] ?>);">
                <i class="bi bi-cart-plus"></i> Add
            </button>
        </div>
    </div>
</div>
