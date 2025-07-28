async function loadUser() {
    document.getElementById('tb').innerHTML = await httpPost('loadUserProcess.php');
}

async function updateUserStatus() {
    const uid = document.getElementById('uid');
    try {
        const response = await httpPost('updateUserStatusProcess.php', { u: uid.value });
        if (response === 'Deactive' || response === 'Active') {
            showSuccess('Success!', 'User status updated successfully');
            uid.value = '';
            loadUser();
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function searchUser() {
    document.getElementById('tb').innerHTML = await httpPost('userSearchProcess.php', {
        user: document.getElementById('user').value,
    });
}

async function addLookup(url, field, key) {
    const el = document.getElementById(field);
    const response = await httpPost(url, { [key]: el.value });
    if (response === 'Success' || response === 'success') {
        showSuccess('Good job!', 'Successfully Added');
        el.value = '';
    } else {
        showError('Oops!', response);
    }
}

function addBrand() { addLookup('brandRegisterProcess.php', 'bName', 'b'); }
function addCategory() { addLookup('categoryRegisterProcess.php', 'catName', 'cat'); }
function addSize() { addLookup('sizeRegisterProcess.php', 'size', 's'); }
function addColor() { addLookup('clrRegisterProcess.php', 'clr', 'col'); }

function changeStockView() {
    document.getElementById('reg').classList.toggle('d-none');
    document.getElementById('update').classList.toggle('d-none');
}

function getSizeOptions() {
    const container = document.getElementById('sizeRows');
    if (!container || !container.dataset.sizeOptions) {
        return [];
    }
    try {
        return JSON.parse(container.dataset.sizeOptions);
    } catch (e) {
        return [];
    }
}

function buildSizeSelectOptions(selectedId) {
    const options = getSizeOptions();
    let html = '<option value="0">Select size</option>';
    options.forEach((opt) => {
        const selected = String(opt.id) === String(selectedId) ? ' selected' : '';
        html += `<option value="${opt.id}"${selected}>${opt.name}</option>`;
    });
    return html;
}

function addSizeRow(selectedId) {
    const container = document.getElementById('sizeRows');
    if (!container) return;

    const row = document.createElement('div');
    row.className = 'admin-size-row';
    row.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label">Size</label>
                <select class="form-select admin-size-select">${buildSizeSelectOptions(selectedId)}</select>
            </div>
            <div class="col-sm-3">
                <label class="form-label">Qty</label>
                <input type="number" class="form-control admin-size-qty" min="0" placeholder="0">
            </div>
            <div class="col-sm-3">
                <label class="form-label">Price (LKR)</label>
                <input type="number" class="form-control admin-size-price" min="0" step="0.01" placeholder="0.00">
            </div>
            <div class="col-sm-2">
                <button type="button" class="admin-btn admin-btn-outline w-100 admin-size-remove" onclick="removeSizeRow(this);" title="Remove size">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(row);
    updateSizeRemoveButtons();
}

function removeSizeRow(button) {
    const container = document.getElementById('sizeRows');
    if (!container || container.children.length <= 1) {
        showError('Oops!', 'At least one size is required');
        return;
    }
    button.closest('.admin-size-row')?.remove();
    updateSizeRemoveButtons();
}

function updateSizeRemoveButtons() {
    const container = document.getElementById('sizeRows');
    if (!container) return;
    const rows = container.querySelectorAll('.admin-size-row');
    rows.forEach((row) => {
        const btn = row.querySelector('.admin-size-remove');
        if (btn) {
            btn.disabled = rows.length <= 1;
        }
    });
}

function getColorOptions() {
    const container = document.getElementById('colorRows');
    if (!container || !container.dataset.colorOptions) {
        return [];
    }
    try {
        return JSON.parse(container.dataset.colorOptions);
    } catch (e) {
        return [];
    }
}

function buildColorSelectOptions(selectedId) {
    const options = getColorOptions();
    let html = '<option value="0">Select color</option>';
    options.forEach((opt) => {
        const selected = String(opt.id) === String(selectedId) ? ' selected' : '';
        html += `<option value="${opt.id}"${selected}>${opt.name}</option>`;
    });
    return html;
}

function addColorRow(selectedId) {
    const container = document.getElementById('colorRows');
    if (!container) return;

    const row = document.createElement('div');
    row.className = 'admin-size-row';
    row.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-sm-10">
                <label class="form-label">Color</label>
                <select class="form-select admin-color-select">${buildColorSelectOptions(selectedId)}</select>
            </div>
            <div class="col-sm-2">
                <button type="button" class="admin-btn admin-btn-outline w-100 admin-color-remove" onclick="removeColorRow(this);" title="Remove color">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(row);
    updateColorRemoveButtons();
}

function removeColorRow(button) {
    const container = document.getElementById('colorRows');
    if (!container || container.children.length <= 1) {
        showError('Oops!', 'At least one color is required');
        return;
    }
    button.closest('.admin-size-row')?.remove();
    updateColorRemoveButtons();
}

function updateColorRemoveButtons() {
    const container = document.getElementById('colorRows');
    if (!container) return;
    const rows = container.querySelectorAll('.admin-size-row');
    rows.forEach((row) => {
        const btn = row.querySelector('.admin-color-remove');
        if (btn) {
            btn.disabled = rows.length <= 1;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const sizeContainer = document.getElementById('sizeRows');
    if (sizeContainer && sizeContainer.children.length === 0) {
        addSizeRow();
    }
    const colorContainer = document.getElementById('colorRows');
    if (colorContainer && colorContainer.children.length === 0) {
        addColorRow();
    }
});

async function regProduct() {
    const f = new FormData();
    f.append('pname', document.getElementById('pname').value);
    f.append('brand', document.getElementById('brand').value);
    f.append('cat', document.getElementById('cat').value);
    f.append('desc', document.getElementById('desc').value);

    const colorRows = document.querySelectorAll('#colorRows .admin-size-row');
    const usedColors = new Set();
    let hasValidColor = false;
    let duplicateColor = false;

    colorRows.forEach((row) => {
        const colorId = row.querySelector('.admin-color-select')?.value;
        if (!colorId || colorId === '0') {
            return;
        }
        if (usedColors.has(colorId)) {
            duplicateColor = true;
            return;
        }
        usedColors.add(colorId);
        hasValidColor = true;
        f.append('color_id[]', colorId);
    });

    if (duplicateColor) {
        showError('Oops!', 'Each color can only be added once');
        return;
    }

    if (!hasValidColor) {
        showError('Oops!', 'Please select at least one color');
        return;
    }

    const sizeRows = document.querySelectorAll('#sizeRows .admin-size-row');
    const usedSizes = new Set();
    let hasValidSize = false;
    let duplicateSize = false;

    sizeRows.forEach((row) => {
        const sizeId = row.querySelector('.admin-size-select')?.value;
        const qty = row.querySelector('.admin-size-qty')?.value || '0';
        const price = row.querySelector('.admin-size-price')?.value || '0';

        if (!sizeId || sizeId === '0') {
            return;
        }
        if (usedSizes.has(sizeId)) {
            duplicateSize = true;
            return;
        }
        usedSizes.add(sizeId);
        hasValidSize = true;
        f.append('size_id[]', sizeId);
        f.append('size_qty[]', qty);
        f.append('size_price[]', price);
    });

    if (duplicateSize) {
        showError('Oops!', 'Each size can only be added once');
        return;
    }

    if (!hasValidSize) {
        showError('Oops!', 'Please select at least one size');
        return;
    }

    const files = document.getElementById('file').files;
    for (let i = 0; i < files.length; i++) {
        f.append('images[]', files[i]);
    }
    const csrf = typeof getCsrfToken === 'function' ? getCsrfToken() : window.CSRF_TOKEN;
    if (csrf) {
        f.append('_csrf', csrf);
    }
    const response = await httpPost('productRegProcess.php', f);
    if (response === 'success') {
        showSuccess('Success!', 'Successfully Registered');
        window.location.reload();
    } else {
        showError('Oops!', response);
    }
}

async function updateStock() {
    const response = await httpPost('updateStockProcess.php', {
        sp: document.getElementById('productSelct').value,
        q: document.getElementById('qty').value,
        p: document.getElementById('price').value,
    });
    if (response === 'success' || response === 'New Stock Added Successfully') {
        showSuccess('Success!', response);
        document.getElementById('productSelct').value = '';
        document.getElementById('qty').value = '';
        document.getElementById('price').value = '';
    } else {
        showError('Oops !', response);
    }
}

async function loadChart() {
    const ctx = document.getElementById('myChart');
    const data = JSON.parse(await httpPost('loadChartProcess.php'));
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Units Sold',
                data: data.data,
                backgroundColor: ['#d4a017', '#2563eb', '#059669', '#7c3aed', '#dc2626'],
                borderWidth: 0,
            }],
        },
        options: { plugins: { legend: { position: 'bottom' } } },
    });
}

async function loadChart2() {
    const ctx = document.getElementById('myChart2');
    const data = JSON.parse(await httpPost('loadChartProcess2.php'));
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.dates,
            datasets: [{
                label: 'Daily Income (LKR)',
                data: data.incomes,
                borderColor: '#d4a017',
                backgroundColor: 'rgba(212, 160, 23, 0.1)',
                fill: true,
                tension: 0.35,
                borderWidth: 2,
            }],
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } },
        },
    });
    const el = document.getElementById('total-amount');
    if (el) {
        el.innerHTML = 'LKR ' + Number(data.total_amount).toLocaleString(undefined, { minimumFractionDigits: 2 });
    }
}

async function loadChart3() {
    const ctx = document.getElementById('myChart3');
    const data = JSON.parse(await httpPost('loadChartProcess3.php'));
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Units Sold',
                data: data.data,
                backgroundColor: '#141824',
                borderRadius: 6,
                borderWidth: 0,
            }],
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } },
        },
    });
}

function chnagepw() {
    new bootstrap.Modal(document.getElementById('fpModal4')).show();
}

async function resetPassword2() {
    const np1 = document.getElementById('newpw1').value;
    const np2 = document.getElementById('renewpw1').value;
    if (np1 !== np2) {
        showError('Password is not Matched', 'Retype Password Again');
        return;
    }
    const response = await httpPost('changePasswordProcess.php', {
        op1: document.getElementById('opw').value,
        n1: np1,
    });
    if (response.trim() === 'Success') {
        showSuccess('Update Successful', response);
    } else {
        showError('Update Failed', response);
    }
}

function showpw3() { togglePassword('newpw1', 'spb3'); }
function showpw4() { togglePassword('renewpw1', 'spb4'); }
function showpw5() { togglePassword('opw', 'spb5'); }

async function updateData() {
    const response = await httpPost('updateDataProcess.php', {
        f: document.getElementById('fname').value,
        l: document.getElementById('lname').value,
        e: document.getElementById('email').value,
        m: document.getElementById('mobile').value,
        n: document.getElementById('no').value,
        l1: document.getElementById('line1').value,
        l2: document.getElementById('line2').value,
    });
    showInfo(response);
}

async function uploadImg() {
    const img = document.getElementById('imgUploader');
    const f = new FormData();
    f.append('i', img.files[0]);
    const csrf = typeof getCsrfToken === 'function' ? getCsrfToken() : window.CSRF_TOKEN;
    if (csrf) {
        f.append('_csrf', csrf);
    }
    const response = await httpPost('profileImgUploadProcess.php', f);
    if (response === 'empty') {
        showError('OOPS !', 'Please Select your Profile Image');
    } else if (response.endsWith('success')) {
        window.location.reload();
    }
}

function togglePassword(fieldId, buttonId) {
    const field = document.getElementById(fieldId);
    const button = document.getElementById(buttonId);
    if (!field || !button) return;
    field.type = field.type === 'password' ? 'text' : 'password';
    button.innerHTML = field.type === 'password'
        ? '<i class="bi bi-eye-slash"></i>'
        : '<i class="bi bi-eye"></i>';
}
