<?php
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$navLinks = [
    'home.php' => ['label' => 'Shop', 'icon' => 'bi-shop'],
    'cart.php' => ['label' => 'Cart', 'icon' => 'bi-cart3'],
    'orderHistory.php' => ['label' => 'Orders', 'icon' => 'bi-clock-history'],
    'profile.php' => ['label' => 'Profile', 'icon' => 'bi-person'],
];
?>
<nav class="navbar navbar-expand-lg lc-navbar">
    <div class="container-fluid px-3">
        <a class="navbar-brand d-flex align-items-center gap-2" href="home.php">
            <img src="<?= resource_url('images/hansi logo jpg.jpg') ?>" alt="Lagatama Craft">
            Lagatama Craft
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#lcNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="lcNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <?php foreach ($navLinks as $href => $link): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === $href ? ' active' : '' ?>" href="<?= $href ?>">
                            <i class="bi <?= $link['icon'] ?> me-1"></i><?= $link['label'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item ms-lg-2">
                    <a href="cart.php" class="lc-cart-btn" title="Cart">
                        <i class="bi bi-cart3"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
