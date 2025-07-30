function initProductGallery() {

    const main = document.getElementById('lcGalleryMain');

    const thumbs = document.querySelectorAll('.lc-gallery-thumb');

    if (!main || !thumbs.length) return;



    thumbs.forEach((thumb) => {

        thumb.addEventListener('click', () => {

            main.src = thumb.dataset.src;

            thumbs.forEach((t) => {

                t.classList.remove('active');

                t.setAttribute('aria-selected', 'false');

            });

            thumb.classList.add('active');

            thumb.setAttribute('aria-selected', 'true');

        });

    });

}



function getSelectedStockId() {

    const el = document.getElementById('selectedStockId');

    return el ? el.value : null;

}



function formatRs(amount) {

    return 'Rs ' + Number(amount).toLocaleString('en-LK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

}



function initProductSizePicker() {

    const dataEl = document.getElementById('lcSizeVariants');

    const picker = document.getElementById('lcSizePicker');

    if (!dataEl || !picker) return;



    let variants;

    try {

        variants = JSON.parse(dataEl.textContent);

    } catch (_) {

        return;

    }

    if (!variants.length) return;



    let priceMeta = {};

    const priceMetaEl = document.getElementById('lcPriceMeta');

    if (priceMetaEl) {

        try {

            priceMeta = JSON.parse(priceMetaEl.textContent);

        } catch (_) {

            priceMeta = {};

        }

    }



    const stockInput = document.getElementById('selectedStockId');

    const qtyInput = document.getElementById('qty');

    const priceEl = document.getElementById('lcProductPrice');

    const priceWasEl = document.getElementById('lcPriceWas');

    const priceDiscountEl = document.getElementById('lcPriceDiscount');

    const priceNoteEl = document.getElementById('lcPriceNote');

    const stockBadge = document.getElementById('lcStockBadge');

    const stockUrgency = document.getElementById('lcStockUrgency');

    const stockUrgencyQty = document.getElementById('lcStockUrgencyQty');

    const selectedSizeEl = document.getElementById('lcSelectedSize');

    const specSize = document.getElementById('lcSpecSize');

    const specPrice = document.getElementById('lcSpecPrice');

    const specAvail = document.getElementById('lcSpecAvail');

    const specSku = document.getElementById('lcSpecSku');

    const purchaseBlock = document.getElementById('lcPurchaseBlock');

    const addBtn = document.getElementById('lcAddCartBtn');

    const buyBtn = document.getElementById('lcBuyNowBtn');

    const deliveryFee = parseFloat(picker.dataset.deliveryFee || priceMeta.delivery_fee || '500');

    const listPrice = priceMeta.list_price ? parseFloat(priceMeta.list_price) : null;



    const variantMap = {};

    variants.forEach((v) => {

        variantMap[String(v.stock_id)] = v;

    });



    function setPurchaseEnabled(enabled) {

        if (addBtn) addBtn.disabled = !enabled;

        if (buyBtn) buyBtn.disabled = !enabled;

        if (qtyInput) qtyInput.disabled = !enabled;

    }



    function updatePriceDisplay(price) {

        if (priceEl) priceEl.textContent = formatRs(price);



        const compareAt = listPrice && listPrice > price ? listPrice : null;

        if (priceWasEl) {

            if (compareAt) {

                priceWasEl.textContent = formatRs(compareAt);

                priceWasEl.classList.remove('d-none');

            } else {

                priceWasEl.classList.add('d-none');

            }

        }

        if (priceDiscountEl) {

            if (compareAt && compareAt > 0) {

                const pct = Math.round(((compareAt - price) / compareAt) * 100);

                priceDiscountEl.textContent = pct + '% off';

                priceDiscountEl.classList.remove('d-none');

            } else {

                priceDiscountEl.classList.add('d-none');

            }

        }

        if (priceNoteEl) {

            const total = parseFloat(price) + deliveryFee;

            priceNoteEl.textContent = 'Delivery from ' + formatRs(deliveryFee) + ' · Total from ' + formatRs(total);

        }

        if (specPrice) specPrice.textContent = formatRs(price);

    }



    function updateStockUrgency(qty) {

        if (!stockUrgency || !stockUrgencyQty) return;

        const show = qty > 0 && qty <= 10;

        stockUrgency.classList.toggle('d-none', !show);

        stockUrgencyQty.textContent = String(qty);

    }



    function selectVariant(stockId) {

        const variant = variantMap[String(stockId)];

        if (!variant) return;



        const qty = parseInt(variant.qty, 10);

        const inStock = qty > 0;



        if (stockInput) stockInput.value = String(variant.stock_id);



        picker.querySelectorAll('.lc-size-option').forEach((btn) => {

            btn.classList.toggle('active', btn.dataset.stockId === String(variant.stock_id));

        });



        document.querySelectorAll('.lc-size-table tr[data-stock-id]').forEach((row) => {

            row.classList.toggle('selected', row.dataset.stockId === String(variant.stock_id));

        });



        updatePriceDisplay(parseFloat(variant.price));



        if (selectedSizeEl) selectedSizeEl.textContent = variant.size_name;



        if (stockBadge) {

            stockBadge.classList.toggle('out', !inStock);

            stockBadge.innerHTML = inStock

                ? '<i class="bi bi-check-circle"></i> In stock'

                : '<i class="bi bi-x-circle"></i> Out of stock';

        }



        updateStockUrgency(qty);



        if (specSize) specSize.textContent = variant.size_name;

        if (specAvail) {

            specAvail.textContent = inStock ? qty + ' units in stock (' + variant.size_name + ')' : 'Currently unavailable';

        }

        if (specSku) {

            specSku.textContent = 'LC-' + String(variant.stock_id).padStart(5, '0');

        }

        const quickSku = document.getElementById('lcQuickSku');

        if (quickSku) {

            quickSku.textContent = 'LC-' + String(variant.stock_id).padStart(5, '0');

        }



        if (qtyInput) {

            qtyInput.max = inStock ? qty : 1;

            qtyInput.min = 1;

            if (!inStock) {

                qtyInput.value = '1';

            } else if (parseInt(qtyInput.value, 10) > qty) {

                qtyInput.value = String(qty);

            } else if (!qtyInput.value || parseInt(qtyInput.value, 10) < 1) {

                qtyInput.value = '1';

            }

        }



        if (purchaseBlock) purchaseBlock.classList.toggle('d-none', !inStock);

        setPurchaseEnabled(inStock);

    }



    picker.querySelectorAll('.lc-size-option').forEach((btn) => {

        btn.addEventListener('click', () => {

            if (btn.classList.contains('unavailable')) return;

            selectVariant(btn.dataset.stockId);

        });

    });



    const initial = stockInput && stockInput.value ? stockInput.value : String(variants[0].stock_id);

    const initialVariant = variantMap[initial];

    if (initialVariant && parseInt(initialVariant.qty, 10) <= 0) {

        const firstInStock = variants.find((v) => parseInt(v.qty, 10) > 0);

        selectVariant(firstInStock ? firstInStock.stock_id : initial);

    } else {

        selectVariant(initial);

    }

}



function initQtyStepper() {

    const qtyInput = document.getElementById('qty');

    const minusBtn = document.getElementById('lcQtyMinus');

    const plusBtn = document.getElementById('lcQtyPlus');

    if (!qtyInput || !minusBtn || !plusBtn) return;



    function clampQty() {

        const min = parseInt(qtyInput.min, 10) || 1;

        const max = parseInt(qtyInput.max, 10) || 1;

        let val = parseInt(qtyInput.value, 10) || 1;

        val = Math.max(min, Math.min(max, val));

        qtyInput.value = String(val);

        minusBtn.disabled = val <= min;

        plusBtn.disabled = val >= max;

    }



    minusBtn.addEventListener('click', () => {

        qtyInput.value = String(Math.max(parseInt(qtyInput.min, 10) || 1, (parseInt(qtyInput.value, 10) || 1) - 1));

        clampQty();

    });



    plusBtn.addEventListener('click', () => {

        qtyInput.value = String(Math.min(parseInt(qtyInput.max, 10) || 1, (parseInt(qtyInput.value, 10) || 1) + 1));

        clampQty();

    });



    clampQty();

}



function initShareButton() {

    const btn = document.getElementById('lcShareBtn');

    if (!btn) return;



    btn.addEventListener('click', async () => {

        const url = btn.dataset.shareUrl || window.location.href;

        const title = document.querySelector('.lc-product-detail-info h1')?.textContent || 'Lagatama Craft';



        if (navigator.share) {

            try {

                await navigator.share({ title, url });

                return;

            } catch (_) {

                // user cancelled or unsupported

            }

        }



        try {

            await navigator.clipboard.writeText(url);

            if (typeof Swal !== 'undefined') {

                Swal.fire({ icon: 'success', title: 'Link copied', timer: 1500, showConfirmButton: false });

            }

        } catch (_) {

            window.prompt('Copy this link:', url);

        }

    });

}



function initWishlistButton() {

    const btn = document.getElementById('lcWishlistBtn');

    const label = document.getElementById('lcWishlistLabel');

    if (!btn) return;



    const stockId = btn.dataset.stockId;

    const key = 'lc-wishlist';

    const icon = btn.querySelector('i');



    function getWishlist() {

        try {

            return JSON.parse(localStorage.getItem(key) || '[]');

        } catch (_) {

            return [];

        }

    }



    function isSaved() {

        return getWishlist().includes(String(stockId));

    }



    function updateUi() {

        const saved = isSaved();

        if (icon) {

            icon.classList.toggle('bi-heart', !saved);

            icon.classList.toggle('bi-heart-fill', saved);

        }

        if (label) label.textContent = saved ? 'Saved' : 'Save';

        btn.classList.toggle('active', saved);

    }



    btn.addEventListener('click', () => {

        let list = getWishlist();

        const id = String(stockId);

        if (list.includes(id)) {

            list = list.filter((x) => x !== id);

        } else {

            list.push(id);

        }

        localStorage.setItem(key, JSON.stringify(list));

        updateUi();

    });



    updateUi();

}



document.addEventListener('DOMContentLoaded', () => {

    initProductGallery();

    initProductSizePicker();

    initQtyStepper();

    initShareButton();

    initWishlistButton();

});

