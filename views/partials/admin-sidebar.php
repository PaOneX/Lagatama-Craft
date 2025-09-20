<?php
/** @var string $activeNav */
$navItems = [
    'dashboard' => ['href' => 'adminDashboard.php', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
    'users'     => ['href' => 'userManagement.php', 'icon' => 'bi-people', 'label' => 'Users'],
    'products'  => ['href' => 'productManagement.php', 'icon' => 'bi-tags', 'label' => 'Products'],
    'stock'     => ['href' => 'stockManagement.php', 'icon' => 'bi-box-seam', 'label' => 'Stock'],
    'landing'   => ['href' => 'landingManagement.php', 'icon' => 'bi-layout-text-window-reverse', 'label' => 'Landing Page'],
    'reports'   => ['href' => 'report.php', 'icon' => 'bi-bar-chart-line', 'label' => 'Reports'],
];
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand">
        <img src="<?= resource_url('images/hansi logo jpg.jpg') ?>" alt="Lagatama Craft" class="admin-brand-icon">
        <div>
            <span class="admin-brand-title">Lagatama Craft</span>
            <span class="admin-brand-sub">Admin Panel</span>
        </div>
    </div>
    <nav class="admin-nav">
        <?php foreach ($navItems as $key => $item): ?>
            <a href="<?= $item['href'] ?>"
               class="admin-nav-link<?= ($activeNav ?? '') === $key ? ' active' : '' ?>">
                <i class="bi <?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="admin-sidebar-footer">
        <a href="../home.php" class="admin-nav-link" target="_blank">
            <i class="bi bi-shop"></i>
            <span>View Store</span>
        </a>
    </div>
</aside>
