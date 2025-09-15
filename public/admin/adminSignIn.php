<?php

require_once dirname(__DIR__) . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Core\Security;

Security::preventCaching();

if (Auth::checkAdmin()) {
    Response::redirect('adminDashboard.php');
}

$googleClientId = config('google.client_id', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In | Lagatama Craft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/bootstrap.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('css/adminDash.css') ?>">
    <link rel="icon" href="<?= resource_url('images/hansi logo jpg.jpg') ?>">
    <meta name="csrf-token" content="<?= \App\Core\Session::csrfToken() ?>">
    <meta name="app-base" content="<?= htmlspecialchars(web_base()) ?>">
</head>
<body class="admin-signin-page">
    <div class="admin-signin-card">
        <div class="admin-signin-logo">
            <img src="<?= resource_url('images/hansi logo jpg.jpg') ?>" alt="Lagatama Craft">
            <h2>Admin Sign In</h2>
            <p>Sign in to manage Lagatama Craft</p>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="admin@example.com" autocomplete="username">
        </div>
        <div class="mb-3">
            <label class="form-label" for="pw1">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="pw1" placeholder="Enter password" autocomplete="current-password">
                <button class="btn btn-outline-secondary" type="button" onclick="showPassword();" id="sp" aria-label="Toggle password">
                    <i class="bi bi-eye-slash-fill"></i>
                </button>
            </div>
        </div>
        <button class="btn-signin" onclick="adminSignIn();">Sign In</button>
        <div class="admin-signin-divider"><span>or continue with</span></div>
        <div id="googleAdminSignInBtn" class="lc-google-btn-host"></div>
    </div>
    <script>window.GOOGLE_CLIENT_ID = <?= json_encode($googleClientId) ?>;</script>
    <?php if ($googleClientId !== ''): ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= csrf_url() ?>"></script>
    <script src="<?= asset('js/core/http.js') ?>"></script>
    <script src="<?= asset('js/core/alerts.js') ?>"></script>
    <script src="<?= asset('js/shop/auth.js') ?>"></script>
</body>
</html>
