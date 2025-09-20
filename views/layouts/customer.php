<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body <?= $bodyAttrs ?? 'data-bs-theme="light"' ?>>
    <script>
    (function(){var t=localStorage.getItem('lc-theme');if(t){document.body.dataset.bsTheme=t;}})();
    </script>
    <?= $content ?? '' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($extraJs)): foreach ((array) $extraJs as $js): ?>
        <script src="<?= htmlspecialchars($js) ?>"></script>
    <?php endforeach; endif; ?>
    <script>window.CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;</script>
    <script src="/assets/js/core/http.js"></script>
    <script src="/assets/js/core/alerts.js"></script>
    <?php if (!empty($pageScripts)): foreach ((array) $pageScripts as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; endif; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var sw = document.getElementById('themeSwitch');
        if (sw) sw.checked = document.body.dataset.bsTheme === 'dark';
    });
    </script>
</body>
</html>
