<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Landing
{
    public static function settings(): array
    {
        $row = Database::fetchOne('SELECT * FROM `landing_settings` WHERE `id` = 1');

        if ($row) {
            return $row;
        }

        return [
            'id' => 1,
            'hero_title' => 'Handcrafted with Love',
            'hero_subtitle' => 'Discover unique bags and accessories made by local artisans.',
            'hero_media_path' => null,
            'hero_media_type' => 'none',
            'updated_at' => null,
        ];
    }

    public static function updateSettings(array $data): void
    {
        Database::query(
            'UPDATE `landing_settings`
             SET `hero_title` = ?, `hero_subtitle` = ?, `hero_media_path` = ?, `hero_media_type` = ?, `updated_at` = NOW()
             WHERE `id` = 1',
            [
                $data['hero_title'],
                $data['hero_subtitle'],
                $data['hero_media_path'],
                $data['hero_media_type'],
            ]
        );
    }

    /** @return list<array> */
    public static function activeOffers(): array
    {
        return Database::fetchAll(
            'SELECT * FROM `landing_offer`
             WHERE `is_active` = 1
               AND (`starts_at` IS NULL OR `starts_at` <= NOW())
               AND (`ends_at` IS NULL OR `ends_at` >= NOW())
             ORDER BY `sort_order` ASC, `id` DESC'
        );
    }

    /** @return list<array> */
    public static function allOffers(): array
    {
        return Database::fetchAll(
            'SELECT * FROM `landing_offer` ORDER BY `sort_order` ASC, `id` DESC'
        );
    }

    public static function offerById(int $id): ?array
    {
        return Database::fetchOne('SELECT * FROM `landing_offer` WHERE `id` = ?', [$id]);
    }

    public static function createOffer(array $data): int
    {
        return Database::insert(
            'INSERT INTO `landing_offer`
                (`title`, `subtitle`, `media_path`, `media_type`, `link_url`, `sort_order`, `is_active`, `starts_at`, `ends_at`, `created_at`)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())',
            [
                $data['title'],
                $data['subtitle'],
                $data['media_path'],
                $data['media_type'],
                $data['link_url'],
                $data['sort_order'],
                $data['is_active'],
                $data['starts_at'],
                $data['ends_at'],
            ]
        );
    }

    public static function updateOffer(int $id, array $data): void
    {
        Database::query(
            'UPDATE `landing_offer`
             SET `title` = ?, `subtitle` = ?, `media_path` = ?, `media_type` = ?, `link_url` = ?,
                 `sort_order` = ?, `is_active` = ?, `starts_at` = ?, `ends_at` = ?
             WHERE `id` = ?',
            [
                $data['title'],
                $data['subtitle'],
                $data['media_path'],
                $data['media_type'],
                $data['link_url'],
                $data['sort_order'],
                $data['is_active'],
                $data['starts_at'],
                $data['ends_at'],
                $id,
            ]
        );
    }

    public static function deleteOffer(int $id): void
    {
        Database::query('DELETE FROM `landing_offer` WHERE `id` = ?', [$id]);
    }

    public static function toggleOffer(int $id, int $active): void
    {
        Database::query('UPDATE `landing_offer` SET `is_active` = ? WHERE `id` = ?', [$active, $id]);
    }
}
