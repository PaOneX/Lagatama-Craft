-- Landing page hero + promotional offers (admin-managed)
-- Run: mysql -u root -p lagatama_craft < database/migrations/003_landing_page.sql

USE `lagatama_craft`;

CREATE TABLE IF NOT EXISTS `landing_settings` (
    `id` TINYINT NOT NULL DEFAULT 1,
    `hero_title` VARCHAR(200) NOT NULL DEFAULT 'Handcrafted with Love',
    `hero_subtitle` TEXT NULL,
    `hero_media_path` VARCHAR(255) NULL,
    `hero_media_type` ENUM('none', 'image', 'video') NOT NULL DEFAULT 'none',
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `landing_offer` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150) NOT NULL,
    `subtitle` VARCHAR(255) NULL,
    `media_path` VARCHAR(255) NOT NULL,
    `media_type` ENUM('image', 'video') NOT NULL DEFAULT 'image',
    `link_url` VARCHAR(500) NULL,
    `sort_order` SMALLINT NOT NULL DEFAULT 0,
    `is_active` TINYINT NOT NULL DEFAULT 1,
    `starts_at` DATETIME NULL,
    `ends_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `landing_offer_active` (`is_active`, `sort_order`)
) ENGINE=InnoDB;

INSERT IGNORE INTO `landing_settings` (`id`, `hero_title`, `hero_subtitle`, `hero_media_type`)
VALUES (
    1,
    'Handcrafted with Love',
    'Discover unique bags and accessories made by local artisans.',
    'none'
);
