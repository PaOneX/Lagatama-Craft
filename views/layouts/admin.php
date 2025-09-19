<?php
/** @var string $pageTitle */
/** @var string $activeNav */
/** @var array $adminData */
/** @var string $content */
/** @var string|null $bodyClass */
/** @var string|null $onload */
/** @var array|null $extraHead */
/** @var array|null $extraScripts */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Lagatama Craft Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/bootstrap.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('css/adminDash.css') ?>">
    <link rel="icon" href="<?= resource_url('images/hansi logo jpg.jpg') ?>">
    <meta name="csrf-token" content="<?= \App\Core\Session::csrfToken() ?>">
    <meta name="app-base" content="<?= htmlspecialchars(web_base()) ?>">
    <?php if (!empty($extraHead)): foreach ((array) $extraHead as $tag): ?>
        <?= $tag ?>
    <?php endforeach; endif; ?>
</head>
<body class="admin-app <?= htmlspecialchars($bodyClass ?? '') ?>"<?= !empty($onload) ? ' onload="' . htmlspecialchars($onload) . '"' : '' ?>>
    <div class="admin-shell">
        <?php include base_path('views/partials/admin-sidebar.php'); ?>
        <div class="admin-main">
            <?php include base_path('views/partials/admin-topbar.php'); ?>
            <main class="admin-content">
                <?= $content ?>
            </main>
        </div>
    </div>
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" onclick="toggleAdminSidebar()"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= csrf_url() ?>"></script>
    <script src="<?= asset('js/core/http.js') ?>"></script>
    <script src="<?= asset('js/core/alerts.js') ?>"></script>
    <script src="<?= asset('js/admin/sidebar.js') ?>"></script>
    <?php if (!empty($extraScripts)): foreach ((array) $extraScripts as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; endif; ?>
    <script src="<?= asset('js/admin/management.js') ?>"></script>
    <script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
    </script>
</body>
</html>
