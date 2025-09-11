<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;
use App\Core\Database;

if (!Auth::checkUser()) {
    header('Location: index.php');
    exit;
}

$user = Auth::user();
$orders = Database::fetchAll(
    'SELECT * FROM `order_history` WHERE `user_id` = ? AND `status` = ? ORDER BY `oh_id` DESC',
    [$user['id'], 'paid']
);

ob_start();
include base_path('views/partials/header.php');
include base_path('views/partials/navbar.php');
?>
<div class="lc-page">
    <div class="lc-page-header">
        <h1 class="lc-page-title">Order History</h1>
        <p class="lc-page-subtitle">View your past purchases</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="lc-empty">
            <i class="bi bi-bag"></i>
            <h2>No orders yet</h2>
            <p>When you make a purchase, it will appear here.</p>
            <a href="home.php" class="lc-btn lc-btn-primary mt-3">Start Shopping</a>
        </div>
    <?php else: foreach ($orders as $d):
        $items = Database::fetchAll(
            'SELECT oi.oi_qty, s.price, p.name FROM `order_items` oi
             INNER JOIN `stock` s ON oi.stock_stock_id = s.stock_id
             INNER JOIN `product` p ON s.product_id = p.id
             WHERE oi.order_history_oh_id = ?',
            [$d['oh_id']]
        );
        $total_amount = 0;
        foreach ($items as $item) {
            $total_amount += $item['price'] * $item['oi_qty'];
        }
    ?>
        <div class="lc-order-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <h5>Order #<?= htmlspecialchars($d['order_id']) ?></h5>
                    <small class="text-muted"><?= htmlspecialchars($d['order_date']) ?></small>
                </div>
                <button type="button" class="lc-btn lc-btn-outline" onclick="window.location.href='invoice.php?orderId=<?= (int) $d['oh_id'] ?>'">
                    <i class="bi bi-receipt"></i> View Invoice
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $d2): ?>
                        <tr>
                            <td><?= htmlspecialchars($d2['name']) ?></td>
                            <td><?= (int) $d2['oi_qty'] ?></td>
                            <td class="text-end">Rs <?= number_format($d2['price'] * $d2['oi_qty'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3 pt-3 border-top">
                <div class="text-muted" style="font-size:0.875rem;">Delivery: Rs 500.00</div>
                <div class="fw-bold" style="font-size:1.125rem;color:var(--lc-accent);">
                    Total: Rs <?= number_format($total_amount + 500, 2) ?>
                </div>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>
<?php include base_path('views/partials/footer.php'); ?>
<?php
$content = ob_get_clean();
$pageScripts = ['assets/js/shop/auth.js'];
include base_path('views/layouts/customer.php');
