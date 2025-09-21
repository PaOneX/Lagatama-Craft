<?php
/** @var array $adminData */
/** @var string $pageTitle */
?>
<header class="admin-topbar">
    <div class="admin-topbar-left">
        <button type="button" class="admin-menu-toggle" onclick="toggleAdminSidebar()" aria-label="Toggle menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h1 class="admin-page-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
            <p class="admin-page-subtitle">Manage your craft store</p>
        </div>
    </div>
    <div class="admin-topbar-right">
        <div class="admin-user-chip">
            <div class="admin-user-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="admin-user-meta">
                <span class="admin-user-name"><?= htmlspecialchars($adminData['fname'] ?? 'Admin') ?></span>
                <span class="admin-user-role">Administrator</span>
            </div>
        </div>
        <a href="adminSignIn.php" class="admin-signout-btn" onclick="adminSignout(); return false;">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign out</span>
        </a>
    </div>
</header>
