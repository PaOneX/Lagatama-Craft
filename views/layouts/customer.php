<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
    (function(){var t=localStorage.getItem('lc-theme');if(t){document.documentElement.dataset.bsTheme=t;}})();
    </script>
    <title><?= htmlspecialchars($title ?? 'Lagatama Craft') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" href="/resources/images/hansi logo jpg.jpg">
    <?php if (!empty($extraCss)): foreach ((array) $extraCss as $css): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
    <?php endforeach; endif; ?>
    <meta name="csrf-token" content="<?= \App\Core\Session::csrfToken() ?>">
    <meta name="app-base" content="<?= htmlspecialchars(web_base()) ?>">
</head>
<body data-bs-theme="light">
    <script>document.body.dataset.bsTheme=document.documentElement.dataset.bsTheme||'light';</script>
    <?= $content ?? '' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($extraJs)): foreach ((array) $extraJs as $js): ?>
        <script src="<?= htmlspecialchars($js) ?>"></script>
    <?php endforeach; endif; ?>
    <script>window.CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;</script>
    <script src="/assets/js/core/theme.js"></script>
    <script src="/assets/js/core/http.js"></script>
    <script src="/assets/js/core/alerts.js"></script>
    <?php if (!empty($pageScripts)): foreach ((array) $pageScripts as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; endif; ?>
</body>
</html>
