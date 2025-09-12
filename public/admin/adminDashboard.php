<?php

require_once dirname(__DIR__) . '/init.php';

use App\Services\AdminService;

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';

admin_layout($pageTitle, $activeNav, function () {
    $admin = new AdminService();
    $stats = $admin->dashboardStats();
    ?>
    <div class="admin-stats">
        <div class="admin-stat-card">
            <div class="admin-stat-icon gold"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="admin-stat-value" id="total-amount">LKR <?= number_format($stats['revenue'], 2) ?></div>
                <div class="admin-stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="admin-stat-value"><?= $stats['users'] ?></div>
                <div class="admin-stat-label">Registered Users</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon green"><i class="bi bi-bag"></i></div>
            <div>
                <div class="admin-stat-value"><?= $stats['products'] ?></div>
                <div class="admin-stat-label">Products</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon purple"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="admin-stat-value"><?= number_format($stats['stock_units']) ?></div>
                <div class="admin-stat-label">Units in Stock</div>
            </div>
        </div>
    </div>

    <div class="admin-chart-grid">
        <div class="admin-chart-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Daily Income</h3>
                    <p class="admin-card-subtitle">Revenue trend over time</p>
                </div>
            </div>
            <canvas id="myChart2"></canvas>
        </div>
        <div class="admin-chart-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Top Products</h3>
                    <p class="admin-card-subtitle">Best sellers by quantity</p>
                </div>
            </div>
            <canvas id="myChart"></canvas>
        </div>
        <div class="admin-chart-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Top Categories</h3>
                    <p class="admin-card-subtitle">Sales by category</p>
                </div>
            </div>
            <canvas id="myChart3"></canvas>
        </div>
    </div>
    <?php
}, [
    'extraHead' => ['<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>'],
    'onload' => 'loadChart(); loadChart2(); loadChart3();',
]);
