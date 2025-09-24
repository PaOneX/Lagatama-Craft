<?php
/** @var array $settings */
/** @var list<array> $offers */

$heroClass = 'lc-hero';
$heroStyle = '';
$heroMedia = '';

if (!empty($settings['hero_media_path']) && ($settings['hero_media_type'] ?? 'none') !== 'none') {
    $heroClass .= ' lc-hero--media';
    $mediaUrl = media_url($settings['hero_media_path']);

    if ($settings['hero_media_type'] === 'video') {
        $heroMedia = '<video class="lc-hero-bg" src="' . htmlspecialchars($mediaUrl) . '" autoplay muted loop playsinline></video>';
    } else {
        $heroStyle = ' style="background-image:url(\'' . htmlspecialchars($mediaUrl, ENT_QUOTES) . '\')"';
    }
}
?>
<div class="<?= $heroClass ?>"<?= $heroStyle ?>>
    <?= $heroMedia ?>
    <div class="lc-hero-content">
        <h1><?= htmlspecialchars($settings['hero_title']) ?></h1>
        <?php if (!empty($settings['hero_subtitle'])): ?>
            <p><?= htmlspecialchars($settings['hero_subtitle']) ?></p>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($offers)): ?>
    <section class="lc-offers" aria-label="Current offers">
        <div class="lc-offers-header">
            <h2>Special Offers</h2>
        </div>
        <div class="lc-offers-grid">
            <?php foreach ($offers as $offer): ?>
                <?php
                $mediaUrl = media_url($offer['media_path']);
                $tag = !empty($offer['link_url']) ? 'a' : 'div';
                $attrs = !empty($offer['link_url'])
                    ? ' href="' . htmlspecialchars($offer['link_url']) . '" class="lc-offer-card lc-offer-card--link"'
                    : ' class="lc-offer-card"';
                ?>
                <<?= $tag ?><?= $attrs ?>>
                    <div class="lc-offer-media">
                        <?php if ($offer['media_type'] === 'video'): ?>
                            <video src="<?= htmlspecialchars($mediaUrl) ?>" autoplay muted loop playsinline></video>
                        <?php else: ?>
                            <img src="<?= htmlspecialchars($mediaUrl) ?>" alt="<?= htmlspecialchars($offer['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="lc-offer-caption">
                        <strong><?= htmlspecialchars($offer['title']) ?></strong>
                        <?php if (!empty($offer['subtitle'])): ?>
                            <span><?= htmlspecialchars($offer['subtitle']) ?></span>
                        <?php endif; ?>
                    </div>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
