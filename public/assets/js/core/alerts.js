function showSuccess(title, text) {
    Swal.fire({ icon: 'success', title, text });
}

function showError(title, text) {
    Swal.fire({ icon: 'error', title, text });
}

function showInfo(text) {
    Swal.fire({ icon: 'info', title: text });
}

function legacySwal(title, text, icon) {
    Swal.fire({ icon: icon || 'info', title, text });
}

const swal = legacySwal;
