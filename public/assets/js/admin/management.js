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

async function regProduct() {
    const f = new FormData();
    f.append('pname', document.getElementById('pname').value);
    f.append('brand', document.getElementById('brand').value);
    f.append('cat', document.getElementById('cat').value);
    f.append('color', document.getElementById('color').value);
    f.append('size', document.getElementById('size').value);
    f.append('desc', document.getElementById('desc').value);
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
