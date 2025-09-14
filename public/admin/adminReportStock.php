<?php

require_once dirname(__DIR__) . '/init.php';

use App\Services\AdminService;

$pageTitle = 'Stock Report';
$activeNav = 'reports';

admin_layout($pageTitle, $activeNav, function () {
    $rows = (new AdminService())->stockReport();
    ?>
    <a href="report.php" class="admin-back-link"><i class="bi bi-arrow-left"></i> Back to Reports</a>
    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Stock ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price (LKR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $d): ?>
                        <tr>
                            <td><?= (int) $d['stock_id'] ?></td>
                            <td><?= htmlspecialchars($d['name']) ?></td>
                            <td><?= (int) $d['qty'] ?></td>
                            <td><?= number_format((float) $d['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="admin-toolbar mt-3">
        <button class="admin-btn admin-btn-outline" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>
    <?php
});
