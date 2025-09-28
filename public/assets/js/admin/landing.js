async function saveLandingHero() {
    const form = new FormData();
    form.append('hero_title', document.getElementById('heroTitle').value);
    form.append('hero_subtitle', document.getElementById('heroSubtitle').value);

    const media = document.getElementById('heroMedia');
    if (media?.files?.[0]) {
        form.append('hero_media', media.files[0]);
    }

    const remove = document.getElementById('removeHeroMedia');
    if (remove?.checked) {
        form.append('remove_hero_media', '1');
    }

    try {
        const response = await httpPost('saveLandingHeroProcess.php', form);
        if (response === 'success') {
            showSuccess('Saved', 'Hero banner updated');
            window.location.reload();
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

let offerModal;

function openOfferForm() {
    document.getElementById('offerModalTitle').textContent = 'Add Offer';
    document.getElementById('offerId').value = '0';
    document.getElementById('offerTitle').value = '';
    document.getElementById('offerSubtitle').value = '';
    document.getElementById('offerLink').value = '';
    document.getElementById('offerSort').value = '0';
    document.getElementById('offerStarts').value = '';
    document.getElementById('offerEnds').value = '';
    document.getElementById('offerActive').checked = true;
    document.getElementById('offerMedia').value = '';
    document.getElementById('offerMediaHint').textContent = 'Required for new offers.';
    offerModal.show();
}

function toLocalInput(value) {
    if (!value) {
        return '';
    }
    const d = new Date(value.replace(' ', 'T'));
    if (Number.isNaN(d.getTime())) {
        return '';
    }
    const pad = (n) => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

function editOffer(offer) {
    document.getElementById('offerModalTitle').textContent = 'Edit Offer';
    document.getElementById('offerId').value = String(offer.id);
    document.getElementById('offerTitle').value = offer.title || '';
    document.getElementById('offerSubtitle').value = offer.subtitle || '';
    document.getElementById('offerLink').value = offer.link_url || '';
    document.getElementById('offerSort').value = String(offer.sort_order ?? 0);
    document.getElementById('offerStarts').value = toLocalInput(offer.starts_at);
    document.getElementById('offerEnds').value = toLocalInput(offer.ends_at);
    document.getElementById('offerActive').checked = Number(offer.is_active) === 1;
    document.getElementById('offerMedia').value = '';
    document.getElementById('offerMediaHint').textContent = 'Leave empty to keep the current image or video.';
    offerModal.show();
}

async function saveLandingOffer() {
    const form = new FormData();
    form.append('offer_id', document.getElementById('offerId').value);
    form.append('title', document.getElementById('offerTitle').value);
    form.append('subtitle', document.getElementById('offerSubtitle').value);
    form.append('link_url', document.getElementById('offerLink').value);
    form.append('sort_order', document.getElementById('offerSort').value);
    form.append('starts_at', document.getElementById('offerStarts').value);
    form.append('ends_at', document.getElementById('offerEnds').value);

    if (document.getElementById('offerActive').checked) {
        form.append('is_active', '1');
    }

    const media = document.getElementById('offerMedia');
    if (media?.files?.[0]) {
        form.append('offer_media', media.files[0]);
    }

    try {
        const response = await httpPost('landingOfferSaveProcess.php', form);
        if (response === 'success') {
            showSuccess('Saved', 'Offer saved successfully');
            window.location.reload();
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function deleteLandingOffer(id) {
    const confirmed = await Swal.fire({
        title: 'Delete this offer?',
        text: 'This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#dc2626',
    });

    if (!confirmed.isConfirmed) {
        return;
    }

    try {
        const response = await httpPost('landingOfferDeleteProcess.php', { id });
        if (response === 'success') {
            showSuccess('Deleted', 'Offer removed');
            window.location.reload();
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function toggleLandingOffer(id) {
    try {
        const response = await httpPost('landingOfferToggleProcess.php', { id });
        if (response === 'Active' || response === 'Hidden') {
            showSuccess('Updated', `Offer is now ${response.toLowerCase()}`);
            window.location.reload();
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('offerModal');
    if (el) {
        offerModal = new bootstrap.Modal(el);
    }
});
