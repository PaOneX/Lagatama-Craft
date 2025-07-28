(function () {
    const STORAGE_KEY = 'lc-theme';

    function applyTheme(theme) {
        const value = theme === 'dark' ? 'dark' : 'light';
        document.documentElement.dataset.bsTheme = value;
        document.body.dataset.bsTheme = value;
        const sw = document.getElementById('themeSwitch');
        if (sw) {
            sw.checked = value === 'dark';
        }
    }

    function themeChange() {
        const next = document.body.dataset.bsTheme === 'dark' ? 'light' : 'dark';
        localStorage.setItem(STORAGE_KEY, next);
        applyTheme(next);
    }

    window.themeChange = themeChange;

    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved) {
        applyTheme(saved);
    }
})();
