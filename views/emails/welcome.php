<?php
ob_start();
$cartTotal = 0.0;
foreach ($cartItems as $item) {
    $cartTotal += (float) $item['price'] * (int) $item['cart_qty'];
}
?>
<p style="margin:0 0 8px;font-size:15px;color:#6b6560;">Hi <?= htmlspecialchars($fname) ?>,</p>
<h1 style="margin:0 0 12px;font-size:26px;font-weight:700;color:#1f1a14;letter-spacing:-0.02em;">Welcome to <?= htmlspecialchars($appName) ?>!</h1>
<p style="margin:0 0 28px;font-size:15px;line-height:1.6;color:#3d3428;">
    Thank you for joining our community of craft lovers. Here are exclusive offers and picks curated just for you.
</p>

<h2 style="margin:0 0 16px;font-size:16px;font-weight:700;color:#1f1a14;">Your welcome offers</h2>
<?php foreach ($promotions as $promo): ?>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:12px;background:#faf8f5;border-radius:10px;border-left:4px solid #a67c00;">
    <tr>
        <td style="padding:16px 18px;">
            <p style="margin:0 0 4px;font-size:14px;font-weight:700;color:#1f1a14;"><?= htmlspecialchars($promo['title']) ?></p>
            <p style="margin:0 0 8px;font-size:13px;color:#6b6560;"><?= htmlspecialchars($promo['description']) ?></p>
            <span style="display:inline-block;padding:4px 12px;background:#1f1a14;color:#f5f0e8;font-size:12px;font-weight:700;border-radius:6px;letter-spacing:0.05em;"><?= htmlspecialchars($promo['code']) ?></span>
        </td>
    </tr>
</table>
<?php endforeach; ?>

<?php if (!empty($cartItems)): ?>
<h2 style="margin:28px 0 16px;font-size:16px;font-weight:700;color:#1f1a14;">Items in your cart</h2>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e0d8;border-radius:10px;overflow:hidden;">
    <?php foreach ($cartItems as $item): ?>
    <tr>
        <td style="padding:12px 16px;border-bottom:1px solid #e5e0d8;">
            <p style="margin:0;font-size:14px;font-weight:600;color:#1f1a14;"><?= htmlspecialchars($item['name']) ?></p>
            <p style="margin:4px 0 0;font-size:12px;color:#6b6560;">
                <?= htmlspecialchars($item['color_name'] ?? '') ?> &middot; <?= htmlspecialchars($item['size_name'] ?? '') ?>
                &middot; Qty <?= (int) $item['cart_qty'] ?>
            </p>
        </td>
        <td align="right" style="padding:12px 16px;border-bottom:1px solid #e5e0d8;white-space:nowrap;font-size:14px;font-weight:600;color:#a67c00;">
            Rs. <?= number_format((float) $item['price'] * (int) $item['cart_qty'], 2) ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td style="padding:14px 16px;font-size:14px;font-weight:700;color:#1f1a14;">Cart total</td>
        <td align="right" style="padding:14px 16px;font-size:15px;font-weight:700;color:#a67c00;">Rs. <?= number_format($cartTotal, 2) ?></td>
    </tr>
</table>
<?php else: ?>
<p style="margin:28px 0 0;font-size:14px;color:#6b6560;line-height:1.6;">
    Your cart is empty — explore our collection and add something you love.
</p>
<?php endif; ?>

<?php if (!empty($featuredProducts)): ?>
<h2 style="margin:28px 0 16px;font-size:16px;font-weight:700;color:#1f1a14;">Recommended for you</h2>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <?php foreach ($featuredProducts as $product): ?>
        <td width="33%" valign="top" style="padding:0 6px 12px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e0d8;border-radius:10px;overflow:hidden;">
                <tr>
                    <td style="padding:0;">
                        <img src="<?= htmlspecialchars(config('app.url') . web_base() . '/' . ltrim($product['path'], '/')) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100%" style="display:block;height:100px;object-fit:cover;">
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 12px;">
                        <p style="margin:0 0 4px;font-size:12px;font-weight:600;color:#1f1a14;line-height:1.3;"><?= htmlspecialchars($product['name']) ?></p>
                        <p style="margin:0;font-size:12px;color:#a67c00;font-weight:700;">Rs. <?= number_format((float) $product['stock_price'], 2) ?></p>
                    </td>
                </tr>
            </table>
        </td>
        <?php endforeach; ?>
    </tr>
</table>
<?php endif; ?>

<table role="presentation" cellspacing="0" cellpadding="0" style="margin:32px auto 0;">
    <tr>
        <td align="center" style="border-radius:10px;background:#a67c00;">
            <a href="<?= htmlspecialchars($shopUrl) ?>" style="display:inline-block;padding:14px 32px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">Start Shopping</a>
        </td>
    </tr>
</table>
<?php
$content = ob_get_clean();
$appName = $appName ?? 'Lagatama Craft';
require __DIR__ . '/layout.php';
