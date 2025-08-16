<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Security;
use App\Core\Upload;
use App\Models\Landing;

class LandingService
{
    private const IMAGE_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    private const VIDEO_EXT = ['mp4', 'webm', 'mov'];

    public function saveHero(array $input, ?array $file): string
    {
        $title = trim($input['hero_title'] ?? '');
        $subtitle = trim($input['hero_subtitle'] ?? '');

        if ($title === '') {
            return 'Please enter a hero title';
        }

        $settings = Landing::settings();
        $mediaPath = $settings['hero_media_path'];
        $mediaType = $settings['hero_media_type'] ?? 'none';

        if (!empty($input['remove_hero_media'])) {
            $this->deleteMediaFile($mediaPath);
            $mediaPath = null;
            $mediaType = 'none';
        }

        if ($file !== null && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $saved = $this->saveMedia($file);
            if ($saved === null) {
                return 'Invalid hero media. Use JPG, PNG, WebP, GIF, MP4, WebM, or MOV.';
            }

            $this->deleteMediaFile($mediaPath);
            $mediaPath = $saved['path'];
            $mediaType = $saved['type'];
        }

        Landing::updateSettings([
            'hero_title' => $title,
            'hero_subtitle' => $subtitle,
            'hero_media_path' => $mediaPath,
            'hero_media_type' => $mediaType,
        ]);

        return 'success';
    }

    public function saveOffer(array $input, ?array $file): string
    {
        $id = (int) ($input['offer_id'] ?? 0);
        $title = trim($input['title'] ?? '');
        $subtitle = trim($input['subtitle'] ?? '') ?: null;
        $linkUrl = trim($input['link_url'] ?? '') ?: null;
        $sortOrder = (int) ($input['sort_order'] ?? 0);
        $isActive = !empty($input['is_active']) ? 1 : 0;
        $startsAt = $this->nullableDate($input['starts_at'] ?? '');
        $endsAt = $this->nullableDate($input['ends_at'] ?? '');

        if ($title === '') {
            return 'Please enter an offer title';
        }

        if (!Security::isSafeHttpUrl($linkUrl)) {
            return 'Invalid offer link URL';
        }

        $existing = $id > 0 ? Landing::offerById($id) : null;
        $mediaPath = $existing['media_path'] ?? null;
        $mediaType = $existing['media_type'] ?? 'image';

        if ($file !== null && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $saved = $this->saveMedia($file);
            if ($saved === null) {
                return 'Invalid media file. Use JPG, PNG, WebP, GIF, MP4, WebM, or MOV.';
            }

            if ($existing) {
                $this->deleteMediaFile($mediaPath);
            }

            $mediaPath = $saved['path'];
            $mediaType = $saved['type'];
        } elseif (!$existing) {
            return 'Please upload an image or video for this offer';
        }

        $payload = [
            'title' => $title,
            'subtitle' => $subtitle,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'link_url' => $linkUrl,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ];

        if ($existing) {
            Landing::updateOffer($id, $payload);
        } else {
            Landing::createOffer($payload);
        }

        return 'success';
    }

    public function deleteOffer(int $id): string
    {
        if ($id <= 0) {
            return 'Invalid offer';
        }

        $offer = Landing::offerById($id);
        if (!$offer) {
            return 'Offer not found';
        }

        $this->deleteMediaFile($offer['media_path']);
        Landing::deleteOffer($id);

        return 'success';
    }

    public function toggleOffer(int $id): string
    {
        if ($id <= 0) {
            return 'Invalid offer';
        }

        $offer = Landing::offerById($id);
        if (!$offer) {
            return 'Offer not found';
        }

        $newStatus = (int) $offer['is_active'] === 1 ? 0 : 1;
        Landing::toggleOffer($id, $newStatus);

        return $newStatus === 1 ? 'Active' : 'Hidden';
    }

    /** @return array{path: string, type: string}|null */
    private function saveMedia(array $file): ?array
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $type = in_array($ext, self::VIDEO_EXT, true) ? 'video' : 'image';

        if ($type === 'image' && !in_array($ext, self::IMAGE_EXT, true)) {
            return null;
        }

        if ($type === 'video' && !in_array($ext, self::VIDEO_EXT, true)) {
            return null;
        }

        $maxImage = (int) config('security.upload_max_image_bytes', 5 * 1024 * 1024);
        $maxVideo = (int) config('security.upload_max_video_bytes', 50 * 1024 * 1024);
        $mimes = $type === 'video' ? Upload::videoMimes() : Upload::imageMimes();
        $maxBytes = $type === 'video' ? $maxVideo : $maxImage;

        if (Upload::validate($file, $mimes, $maxBytes) !== null) {
            return null;
        }

        $dir = BASE_PATH . '/resources/landing';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = uniqid('landing_', true) . '.' . $ext;
        $path = 'resources/landing/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], BASE_PATH . '/' . $path)) {
            return null;
        }

        return ['path' => $path, 'type' => $type];
    }

    private function deleteMediaFile(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        $full = BASE_PATH . '/' . ltrim($path, '/');
        if (is_file($full)) {
            unlink($full);
        }
    }

    private function nullableDate(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $ts = strtotime($value);
        return $ts === false ? null : date('Y-m-d H:i:s', $ts);
    }
}
