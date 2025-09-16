<?php

require_once dirname(__DIR__) . '/init.php';

use App\Core\Auth;
use App\Models\Landing;

if (!Auth::checkAdmin()) {
    header('Location: adminSignIn.php');
    exit;
}

$pageTitle = 'Landing Page';
$activeNav = 'landing';
$settings = Landing::settings();
$offers = Landing::allOffers();

admin_layout($pageTitle, $activeNav, function () use ($settings, $offers) {
    $heroMediaUrl = !empty($settings['hero_media_path']) ? media_url($settings['hero_media_path']) : '';
    ?>
    <div class="admin-page-intro">
        <p>Manage the shop home page — hero banner text, background media, and promotional offers shown to customers.</p>
    </div>

    <div class="admin-card mb-4" style="max-width: 720px;">
        <h3 class="admin-card-title mb-4"><i class="bi bi-image me-2"></i>Hero Banner</h3>
        <div class="mb-3">
            <label class="form-label" for="heroTitle">Heading</label>
            <input type="text" class="form-control" id="heroTitle" value="<?= htmlspecialchars($settings['hero_title']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="heroSubtitle">Subheading</label>
            <textarea class="form-control" id="heroSubtitle" rows="2"><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="heroMedia">Background image or video (optional)</label>
            <input type="file" class="form-control" id="heroMedia" accept="image/*,video/mp4,video/webm,video/quicktime">
            <small class="text-muted">Replaces the default gradient when set. JPG, PNG, WebP, GIF, MP4, WebM, MOV.</small>
        </div>
        <?php if ($heroMediaUrl !== ''): ?>
            <div class="mb-3" id="heroMediaPreview">
                <div class="lc-admin-media-preview">
                    <?php if (($settings['hero_media_type'] ?? '') === 'video'): ?>
                        <video src="<?= htmlspecialchars($heroMediaUrl) ?>" controls muted></video>
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($heroMediaUrl) ?>" alt="Hero preview">
                    <?php endif; ?>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="removeHeroMedia">
                    <label class="form-check-label" for="removeHeroMedia">Remove current background media</label>
                </div>
            </div>
        <?php endif; ?>
        <button class="admin-btn admin-btn-primary" type="button" onclick="saveLandingHero();">Save Hero</button>
    </div>

    <div class="admin-card">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h3 class="admin-card-title mb-0"><i class="bi bi-megaphone me-2"></i>Offers &amp; Promotions</h3>
            <button class="admin-btn admin-btn-primary" type="button" onclick="openOfferForm();">
                <i class="bi bi-plus-lg"></i> Add Offer
            </button>
        </div>

        <?php if (empty($offers)): ?>
            <p class="text-muted mb-0">No offers yet. Add images or videos to highlight sales and promotions on the shop page.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle admin-table">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Title</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Schedule</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offers as $offer): ?>
                            <tr>
                                <td>
                                    <div class="lc-admin-thumb">
                                        <?php if ($offer['media_type'] === 'video'): ?>
                                            <video src="<?= htmlspecialchars(media_url($offer['media_path'])) ?>" muted></video>
                                            <span class="lc-admin-thumb-badge"><i class="bi bi-play-fill"></i></span>
                                        <?php else: ?>
                                            <img src="<?= htmlspecialchars(media_url($offer['media_path'])) ?>" alt="">
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($offer['title']) ?></strong>
                                    <?php if (!empty($offer['subtitle'])): ?>
                                        <div class="text-muted small"><?= htmlspecialchars($offer['subtitle']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= (int) $offer['sort_order'] ?></td>
                                <td>
                                    <span class="badge <?= (int) $offer['is_active'] === 1 ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= (int) $offer['is_active'] === 1 ? 'Live' : 'Hidden' ?>
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <?php if ($offer['starts_at'] || $offer['ends_at']): ?>
                                        <?= $offer['starts_at'] ? date('M j, Y', strtotime($offer['starts_at'])) : 'Anytime' ?>
                                        &rarr;
                                        <?= $offer['ends_at'] ? date('M j, Y', strtotime($offer['ends_at'])) : 'No end' ?>
                                    <?php else: ?>
                                        Always on
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <button class="btn btn-sm btn-outline-primary" type="button"
                                            onclick='editOffer(<?= json_encode($offer, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);'>
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                            onclick="toggleLandingOffer(<?= (int) $offer['id'] ?>);">
                                        <?= (int) $offer['is_active'] === 1 ? 'Hide' : 'Show' ?>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" type="button"
                                            onclick="deleteLandingOffer(<?= (int) $offer['id'] ?>);">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="offerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="offerModalTitle">Add Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="offerId" value="0">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="offerTitle">Title</label>
                            <input type="text" class="form-control" id="offerTitle" placeholder="e.g. Summer Sale — 20% Off">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="offerSort">Display order</label>
                            <input type="number" class="form-control" id="offerSort" value="0" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="offerSubtitle">Subtitle (optional)</label>
                            <input type="text" class="form-control" id="offerSubtitle" placeholder="Short description shown on the card">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="offerLink">Link URL (optional)</label>
                            <input type="url" class="form-control" id="offerLink" placeholder="https://... or home.php">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="offerStarts">Starts (optional)</label>
                            <input type="datetime-local" class="form-control" id="offerStarts">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="offerEnds">Ends (optional)</label>
                            <input type="datetime-local" class="form-control" id="offerEnds">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="offerMedia">Image or video</label>
                            <input type="file" class="form-control" id="offerMedia" accept="image/*,video/mp4,video/webm,video/quicktime">
                            <small class="text-muted" id="offerMediaHint">Required for new offers. Leave empty when editing to keep the current file.</small>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="offerActive" checked>
                                <label class="form-check-label" for="offerActive">Show on shop page</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="admin-btn admin-btn-primary" onclick="saveLandingOffer();">Save Offer</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}, [
    'extraScripts' => [asset('js/admin/landing.js')],
]);
