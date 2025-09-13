<?php

require_once dirname(__DIR__) . '/init.php';

use App\Services\AdminService;

$filterDate = isset($_GET['date']) ? (string) $_GET['date'] : date('Y-m-d');

$pageTitle = 'Sales Report';
$activeNav = 'reports';

admin_layout($pageTitle, $activeNav, function () use ($filterDate) {
    $report = (new AdminService())->salesReport($filterDate);
    $rows = $report['rows'];
    $netTotal = $report['netTotal'];
    ?>
    <a href="report.php" class="admin-back-link"><i class="bi bi-arrow-left"></i> Back to Reports</a>

    <div class="admin-card mb-4">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label" for="date">Select Date</label>
                <input class="form-control" type="date" id="date" name="date" value="<?= htmlspecialchars($filterDate) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="admin-btn admin-btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order Date & Time</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price (LKR)</th>
                        <th>Total (LKR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No sales recorded for this date.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['Order_Date']) ?></td>
                                <td><?= htmlspecialchars($d['Product_Name']) ?></td>
                                <td><?= (int) $d['Quantity'] ?></td>
                                <td><?= number_format((float) $d['Price'], 2) ?></td>
                                <td><?= number_format((float) $d['Total_Price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Net Total</th>
                        <th>LKR <?= number_format($netTotal, 2) ?></th>
                    </tr>
                </tfoot>
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
