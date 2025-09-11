<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;
use App\Services\CheckoutService;

if (!Auth::checkUser()) {
    header('Location: index.php');
    exit;
}

$user = Auth::user();
$ohId = (int) ($_GET['orderId'] ?? 0);
$pending = isset($_GET['pending']);

$order = (new CheckoutService())->getOrderForInvoice($ohId, (int) $user['id']);

if ($order === null && !$pending) {
    header('Location: orderHistory.php');
    exit;
}

if ($order === null) {
    $order = ['order_id' => 'Pending', 'order_date' => date('Y-m-d H:i:s'), 'amount' => 0, 'items' => []];
}

ob_start();
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 body-main">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4"><img class="img" alt="Invoice" src="resources/invoice.webp" /></div>
                    <div class="col-md-8 text-end">
                        <img src="resources/images/hansi logo jpg.jpg" class="rounded-circle" height="80" alt="Logo">
                        <h4 style="color:#F81D2D;"><strong>Lagatama Craft</strong></h4>
                    </div>
                    <div class="col-md-8 text-start mt-3">
                        <p><?= htmlspecialchars($user['fname'] . ' ' . $user['lname']) ?></p>
                        <p><?= htmlspecialchars($user['mobile']) ?></p>
                        <p><?= htmlspecialchars($user['no'] ?? '') ?></p>
                        <p><?= htmlspecialchars($user['line_1'] ?? '') ?></p>
                        <p><?= htmlspecialchars($user['line_2'] ?? '') ?></p>
                    </div>
                </div>
                <div class="border border-4 p-5 mt-3">
                    <h2 class="text-center fw-bold">INVOICE</h2>
                    <?php if ($pending): ?><div class="alert alert-warning">Payment is being confirmed. Refresh shortly if items are missing.</div><?php endif; ?>
                    <h5>Order Id: <?= htmlspecialchars((string) $order['order_id']) ?></h5>
                    <table class="table table-striped mt-3">
                        <thead class="table-dark">
                            <tr><th>Product</th><th>Qty</th><th>Price</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= (int) $item['oi_qty'] ?></td>
                                <td>Rs.<?= $item['price'] * $item['oi_qty'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <p>Delivery Fee: Rs 500</p>
                        <p><strong>Net Total: Rs <?= htmlspecialchars((string) $order['amount']) ?></strong></p>
                        <p>Date: <?= htmlspecialchars($order['order_date']) ?></p>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-warning" onclick="window.print()">Print</button>
                    <button class="btn btn-light ms-2" onclick="window.location.href='home.php'">Go Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Invoice | Lagatama Craft';
$extraCss = ['assets/css/invoice.css'];
include base_path('views/layouts/customer.php');
