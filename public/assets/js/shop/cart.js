async function quickAddToCart(stockId) {
    try {
        const response = await httpPost('addtoCartProcess.php', { s: stockId, q: 1 });
        showInfo(response);
    } catch (e) {
        showError('Error', e.message);
    }
}

async function addtoCart(stockId) {
    const resolved = stockId || (typeof getSelectedStockId === 'function' ? getSelectedStockId() : null);
    if (!resolved) {
        showError('Select a size', 'Please choose a size before adding to cart.');
        return;
    }
    const qty = document.getElementById('qty');
    if (!qty || qty.value === '' || qty.value <= 0) {
        showError('Oops', 'Please enter a valid quantity');
        return;
    }
    if (qty.max && parseInt(qty.value, 10) > parseInt(qty.max, 10)) {
        showError('Stock limit', 'Only ' + qty.max + ' available in this size.');
        return;
    }
    try {
        const response = await httpPost('addtoCartProcess.php', { s: resolved, q: qty.value });
        showInfo(response);
        qty.value = '1';
    } catch (e) {
        showError('Error', e.message);
    }
}

async function loadCart() {
    const el = document.getElementById('cartBody');
    if (!el) return;
    el.innerHTML = '<div class="lc-loading"><div class="spinner-border" role="status"></div><p class="mt-2">Loading cart...</p></div>';
    try {
        el.innerHTML = await httpPost('loadCartProcess.php');
    } catch (e) {
        el.innerHTML = `<div class="lc-empty"><i class="bi bi-exclamation-circle"></i><h2>Could not load cart</h2><p>${e.message}</p></div>`;
    }
}

async function incrementCartQty(cartId) {
    const qtyInput = document.getElementById('qty' + cartId);
    const current = qtyInput ? parseInt(qtyInput.value, 10) : 1;
    const max = qtyInput ? parseInt(qtyInput.dataset.max || qtyInput.max, 10) : Infinity;
    if (current >= max) {
        showError('Stock limit', 'Only ' + max + ' available in this size.');
        return;
    }
    await updateCartQty(cartId, current + 1);
}

async function decrementCartQty(cartId) {
    const qtyInput = document.getElementById('qty' + cartId);
    const current = qtyInput ? parseInt(qtyInput.value, 10) : 1;
    if (current - 1 > 0) {
        await updateCartQty(cartId, current - 1);
    }
}

async function updateCartQty(cartId, newQty) {
    try {
        const response = await httpPost('updateCartQtyProcess.php', { c: cartId, q: newQty });
        if (response === 'Success') {
            loadCart();
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function removeCart(cartId) {
    const result = await Swal.fire({
        title: 'Remove item?',
        text: 'This will remove the item from your cart.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#a67c00',
        confirmButtonText: 'Remove',
    });
    if (!result.isConfirmed) return;
    try {
        const response = await httpPost('removeCartProcess.php', { c: cartId });
        showInfo(response);
        loadCart();
    } catch (e) {
        showError('Error', e.message);
    }
}
