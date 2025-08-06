-- Lagatama Craft database schema
-- Import: mysql -u root -p lagatama_craft < database/schema.sql

CREATE DATABASE IF NOT EXISTS `lagatama_craft` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lagatama_craft`;

CREATE TABLE IF NOT EXISTS `user_type` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `type_name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `gender` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `gender` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `user` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `fname` VARCHAR(20) NOT NULL,
    `lname` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `mobile` VARCHAR(10) NOT NULL,
    `joined_date` DATETIME NOT NULL,
    `gender_id` INT NOT NULL,
    `status` TINYINT NOT NULL DEFAULT 1,
    `user_type_id` INT NOT NULL DEFAULT 2,
    `verification_code` VARCHAR(100) NULL,
    `verification_expires` DATETIME NULL,
    `google_id` VARCHAR(100) NULL,
    `no` VARCHAR(10) NULL,
    `line_1` VARCHAR(50) NULL,
    `line_2` VARCHAR(50) NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `user_google_id` (`google_id`),
    KEY `gender_id` (`gender_id`),
    KEY `user_type_id` (`user_type_id`),
    CONSTRAINT `user_gender_fk` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
    CONSTRAINT `user_type_fk` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `brand` (
    `brand_id` INT NOT NULL AUTO_INCREMENT,
    `brand_name` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `category` (
    `cat_id` INT NOT NULL AUTO_INCREMENT,
    `cat_name` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `color` (
    `color_id` INT NOT NULL AUTO_INCREMENT,
    `color_name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`color_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `size` (
    `size_id` INT NOT NULL AUTO_INCREMENT,
    `size_name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`size_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `product` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `brand_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    `color_id` INT NOT NULL,
    `size_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `brand_id` (`brand_id`),
    KEY `category_id` (`category_id`),
    KEY `color_id` (`color_id`),
    KEY `size_id` (`size_id`),
    CONSTRAINT `product_brand_fk` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`),
    CONSTRAINT `product_category_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`cat_id`),
    CONSTRAINT `product_color_fk` FOREIGN KEY (`color_id`) REFERENCES `color` (`color_id`),
    CONSTRAINT `product_size_fk` FOREIGN KEY (`size_id`) REFERENCES `size` (`size_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `product_image` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `product_id` INT NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `sort_order` TINYINT NOT NULL DEFAULT 0,
    `is_primary` TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `product_image_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `stock` (
    `stock_id` INT NOT NULL AUTO_INCREMENT,
    `price` DECIMAL(10,2) NOT NULL,
    `qty` INT NOT NULL DEFAULT 0,
    `product_id` INT NOT NULL,
    PRIMARY KEY (`stock_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `stock_product_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `cart` (
    `cart_id` INT NOT NULL AUTO_INCREMENT,
    `cart_qty` INT NOT NULL,
    `user_id` INT NOT NULL,
    `stock_stock_id` INT NOT NULL,
    PRIMARY KEY (`cart_id`),
    KEY `user_id` (`user_id`),
    KEY `stock_stock_id` (`stock_stock_id`),
    CONSTRAINT `cart_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
    CONSTRAINT `cart_stock_fk` FOREIGN KEY (`stock_stock_id`) REFERENCES `stock` (`stock_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `order_history` (
    `oh_id` INT NOT NULL AUTO_INCREMENT,
    `order_id` VARCHAR(50) NOT NULL,
    `order_date` DATETIME NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `user_id` INT NOT NULL,
    `status` ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
    `payhere_payment_id` VARCHAR(100) NULL,
    PRIMARY KEY (`oh_id`),
    KEY `user_id` (`user_id`),
    KEY `order_id` (`order_id`),
    CONSTRAINT `order_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `order_items` (
    `oi_id` INT NOT NULL AUTO_INCREMENT,
    `oi_qty` INT NOT NULL,
    `order_history_oh_id` INT NOT NULL,
    `stock_stock_id` INT NOT NULL,
    PRIMARY KEY (`oi_id`),
    KEY `order_history_oh_id` (`order_history_oh_id`),
    KEY `stock_stock_id` (`stock_stock_id`),
    CONSTRAINT `order_items_order_fk` FOREIGN KEY (`order_history_oh_id`) REFERENCES `order_history` (`oh_id`),
    CONSTRAINT `order_items_stock_fk` FOREIGN KEY (`stock_stock_id`) REFERENCES `stock` (`stock_id`)
) ENGINE=InnoDB;

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

-- Seed lookup data
INSERT IGNORE INTO `user_type` (`id`, `type_name`) VALUES (1, 'Admin'), (2, 'Customer');
INSERT IGNORE INTO `gender` (`id`, `gender`) VALUES (1, 'Male'), (2, 'Female'), (3, 'Other');

INSERT IGNORE INTO `landing_settings` (`id`, `hero_title`, `hero_subtitle`, `hero_media_type`)
VALUES (
    1,
    'Handcrafted with Love',
    'Discover unique bags and accessories made by local artisans.',
    'none'
);

-- Migration for existing databases (run manually if upgrading)
-- ALTER TABLE `order_history` ADD COLUMN `status` ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending';
-- ALTER TABLE `order_history` ADD COLUMN `payhere_payment_id` VARCHAR(100) NULL;
