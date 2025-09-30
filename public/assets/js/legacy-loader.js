/**
 * Legacy script loader for admin pages still referencing script.js
 */
(function () {
    if (typeof window.CSRF_TOKEN === 'undefined') {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'csrf.php', false);
        try { xhr.send(); eval(xhr.responseText); } catch (_) {}
    }

    [
        'assets/js/core/http.js',
        'assets/js/core/alerts.js',
        'assets/js/shop/auth.js',
        'assets/js/shop/products.js',
        'assets/js/shop/cart.js',
        'assets/js/shop/checkout.js',
        'assets/js/admin/management.js',
    ].forEach(function (src) {
        if (!document.querySelector('script[src="' + src + '"]')) {
            const s = document.createElement('script');
            s.src = src;
            document.body.appendChild(s);
        }
    });
})();
