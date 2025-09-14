<?php

require_once dirname(__DIR__) . '/init.php';

use App\Services\AdminService;

$pageTitle = 'User Report';
$activeNav = 'reports';

admin_layout($pageTitle, $activeNav, function () {
    $rows = (new AdminService())->userReport();
    ?>
    <a href="report.php" class="admin-back-link"><i class="bi bi-arrow-left"></i> Back to Reports</a>
    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $d): ?>
                        <tr>
                            <td><?= (int) $d['id'] ?></td>
                            <td><?= htmlspecialchars($d['fname']) ?></td>
                            <td><?= htmlspecialchars($d['lname']) ?></td>
                            <td><?= htmlspecialchars($d['email']) ?></td>
                            <td><?= htmlspecialchars($d['mobile']) ?></td>
                            <td><?= htmlspecialchars($d['type']) ?></td>
                            <td>
                                <?php if ((int) $d['status'] === 1): ?>
                                    <span class="admin-badge admin-badge-success">Active</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($d['joined_date']) ?></td>
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
