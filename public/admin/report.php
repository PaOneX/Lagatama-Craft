<?php

require_once dirname(__DIR__) . '/init.php';

$pageTitle = 'Reports';
$activeNav = 'reports';

admin_layout($pageTitle, $activeNav, function () {
    ?>
    <div class="admin-page-intro">
        <p>Generate and export reports for users, products, stock, and sales.</p>
    </div>
    <div class="admin-report-grid">
        <a href="adminReportUser.php" class="admin-report-card">
            <img src="<?= resource_url('userManage.jpg') ?>" alt="User Report">
            <div class="admin-report-card-body">
                <i class="bi bi-people"></i>
                <span>User Report</span>
            </div>
        </a>
        <a href="adminReportProduct.php" class="admin-report-card">
            <img src="<?= resource_url('productManag.jpg') ?>" alt="Product Report">
            <div class="admin-report-card-body">
                <i class="bi bi-bag"></i>
                <span>Product Report</span>
            </div>
        </a>
        <a href="adminReportStock.php" class="admin-report-card">
            <img src="<?= resource_url('stockManag.png') ?>" alt="Stock Report">
            <div class="admin-report-card-body">
                <i class="bi bi-box-seam"></i>
                <span>Stock Report</span>
            </div>
        </a>
        <a href="adminReportSales.php" class="admin-report-card">
            <img src="<?= resource_url('stockManag.png') ?>" alt="Sales Report">
            <div class="admin-report-card-body">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Sales Report</span>
            </div>
        </a>
    </div>
    <?php
});
