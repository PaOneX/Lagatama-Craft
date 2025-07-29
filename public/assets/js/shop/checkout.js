async function checkOut() {
    try {
        const response = await httpPost('paymentProcess.php', { cart: 'true' });
        const payment = JSON.parse(response);
        startPayHere(payment);
    } catch (e) {
        showError('Checkout Error', e.message);
    }
}

async function buyNow(stockId) {
    const resolved = stockId || (typeof getSelectedStockId === 'function' ? getSelectedStockId() : null);
    if (!resolved) {
        showError('Select a size', 'Please choose a size before checkout.');
        return;
    }
    const qty = document.getElementById('qty');
    if (!qty || qty.value <= 0) {
        showError('Error', 'Please enter a valid quantity');
        return;
    }
    if (qty.max && parseInt(qty.value, 10) > parseInt(qty.max, 10)) {
        showError('Stock limit', 'Only ' + qty.max + ' available in this size.');
        return;
    }
    try {
        const response = await httpPost('paymentProcess.php', {
            cart: 'false',
            stockId: resolved,
            qty: qty.value,
        });
        const payment = JSON.parse(response);
        startPayHere(payment);
    } catch (e) {
        showError('Checkout Error', e.message);
    }
}

function startPayHere(payment) {
    payhere.onCompleted = async function onCompleted(orderId) {
        const ohId = payment.oh_id;
        let attempts = 0;
        const poll = async () => {
            try {
                const status = await httpPost('api/checkout/status.php', { oh_id: ohId });
                if (status === 'paid') {
                    window.location = 'invoice.php?orderId=' + ohId;
                    return;
                }
            } catch (_) {}
            attempts++;
            if (attempts < 15) {
                setTimeout(poll, 2000);
            } else {
                window.location = 'invoice.php?orderId=' + ohId + '&pending=1';
            }
        };
        poll();
    };

    payhere.onDismissed = function () {
        showError('Payment Cancelled', 'You closed the payment window.');
    };

    payhere.onError = function (error) {
        showError('Payment Error', String(error));
    };

    payhere.startPayment(payment);
}

async function setAddress() {
    try {
        const response = await httpPost('shippingAddressProcess.php', {
            no: document.getElementById('no1').value,
            l1: document.getElementById('line1').value,
            l2: document.getElementById('line2').value,
        });
        if (response === 'success') {
            showSuccess('Updated', 'Set Your Shipping Address');
        } else {
            showError('Something Went Wrong', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

function shipAdd() {
    new bootstrap.Modal(document.getElementById('staticBackdrop')).show();
}

function viewShip() {
    const el = document.getElementById('ship');
    el.classList.toggle('d-none');
    el.classList.toggle('d-block');
}

function alert5() {
    showError('Oops', 'Login First');
}
