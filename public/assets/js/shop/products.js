function skeletonHtml(count = 4) {
    let html = '<div class="lc-skeleton-grid">';
    for (let i = 0; i < count; i++) {
        html += '<div class="lc-skeleton-card"><div class="lc-skeleton-img"></div><div class="lc-skeleton-text short"></div><div class="lc-skeleton-text long"></div></div>';
    }
    html += '</div>';
    return html;
}

function setProductLoading(elementId) {
    const el = document.getElementById(elementId);
    if (el) el.innerHTML = skeletonHtml();
}

let searchTimer = null;

function initShop() {
    const saved = localStorage.getItem('lc-theme');
    if (saved) {
        document.body.dataset.bsTheme = saved;
        const sw = document.getElementById('themeSwitch');
        if (sw) sw.checked = saved === 'dark';
    }

    const searchInput = document.getElementById('sProduct');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const term = searchInput.value.trim();
                if (term.length === 0) {
                    loadProduct(0);
                } else {
                    searchProduct(0);
                }
            }, 350);
        });
    }
}

async function loadProduct(page) {
    setProductLoading('pid');
    try {
        document.getElementById('pid').innerHTML = await httpPost('loadProductProcess.php', { p: page });
    } catch (e) {
        document.getElementById('pid').innerHTML = `<div class="lc-empty"><i class="bi bi-exclamation-circle"></i><h2>Something went wrong</h2><p>${e.message}</p></div>`;
    }
}

async function searchProduct(page) {
    setProductLoading('pid');
    try {
        document.getElementById('pid').innerHTML = await httpPost('searchProductProcess.php', {
            p: document.getElementById('sProduct').value,
            pg: page,
        });
    } catch (e) {
        document.getElementById('pid').innerHTML = `<div class="lc-empty"><i class="bi bi-exclamation-circle"></i><h2>Search failed</h2><p>${e.message}</p></div>`;
    }
}

function advSearch() {
    const filterElement = document.getElementById('filterId');
    const toggle = document.getElementById('filterToggle');
    const isHidden = filterElement.classList.contains('d-none');
    filterElement.classList.toggle('d-none', !isHidden);
    if (toggle) toggle.classList.toggle('lc-btn-primary', isHidden);
}

async function advSearchProduct(page) {
    setProductLoading('pid');
    try {
        document.getElementById('pid').innerHTML = await httpPost('advSearchProductProcess.php', {
            pg: page,
            co: document.getElementById('color').value,
            cat: document.getElementById('cat').value,
            b: document.getElementById('brand').value,
            s: document.getElementById('size').value,
            min: document.getElementById('min').value,
            max: document.getElementById('max').value,
        });
    } catch (e) {
        document.getElementById('pid').innerHTML = `<div class="lc-empty"><i class="bi bi-exclamation-circle"></i><h2>Filter failed</h2><p>${e.message}</p></div>`;
    }
}

