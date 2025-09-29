function appBase() {
    const meta = document.querySelector('meta[name="app-base"]');
    const base = meta ? meta.content.replace(/\/$/, '') : '';
    return base;
}

function getCsrfToken() {
    if (window.CSRF_TOKEN) {
        return window.CSRF_TOKEN;
    }
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) {
        window.CSRF_TOKEN = meta.content;
        return meta.content;
    }
    return '';
}

function resolveApiUrl(url) {
    const base = appBase();
    if (/^(https?:)?\/\//.test(url)) {
        return url;
    }
    if (url.startsWith('/')) {
        return base + url;
    }

    const inAdmin = window.location.pathname.includes('/admin/');
    if (inAdmin) {
        if (window.location.pathname.includes('/public/admin/')) {
            return base.replace(/\/$/, '') + '/' + url;
        }
        return base + '/../' + url;
    }
    return base + '/' + url;
}

async function httpPost(url, data = {}, options = {}) {
    const formData = data instanceof FormData ? data : new FormData();
    if (!(data instanceof FormData)) {
        Object.entries(data).forEach(([key, value]) => formData.append(key, value));
    }
    const csrf = getCsrfToken();
    if (csrf && !formData.has('_csrf')) {
        formData.append('_csrf', csrf);
    }

    const response = await fetch(resolveApiUrl(url), { method: 'POST', body: formData, ...options });
    if (!response.ok) {
        throw new Error(await response.text() || `Request failed (${response.status})`);
    }
    return response.text();
}

async function httpGet(url) {
    const response = await fetch(resolveApiUrl(url));
    if (!response.ok) {
        throw new Error(await response.text() || `Request failed (${response.status})`);
    }
    return response.text();
}

function setLoading(elementId, message = 'Loading...') {
    const el = document.getElementById(elementId);
    if (el) {
        el.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">${message}</p></div>`;
    }
}
