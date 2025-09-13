<?php

require_once dirname(__DIR__) . '/init.php';

use App\Services\AdminService;

$pageTitle = 'Product Report';
$activeNav = 'reports';

admin_layout($pageTitle, $activeNav, function () {
    $rows = (new AdminService())->productReport();
    ?>
    <a href="report.php" class="admin-back-link"><i class="bi bi-arrow-left"></i> Back to Reports</a>
    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Color</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Description</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $d): ?>
                        <tr>
                            <td><?= (int) $d['id'] ?></td>
                            <td><?= htmlspecialchars($d['name']) ?></td>
                            <td><?= htmlspecialchars($d['brand_name']) ?></td>
                            <td><?= htmlspecialchars($d['color_name']) ?></td>
                            <td><?= htmlspecialchars($d['cat_name']) ?></td>
                            <td><?= htmlspecialchars($d['size_name']) ?></td>
                            <td><?= htmlspecialchars($d['description']) ?></td>
                            <td><img src="/<?= htmlspecialchars(ltrim($d['path'], '/')) ?>" height="60" alt="" style="border-radius:8px;"></td>
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
