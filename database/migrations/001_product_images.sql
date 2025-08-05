-- Multiple images per product
-- Run: mysql -u root -p lagatama_craft < database/migrations/001_product_images.sql

USE `lagatama_craft`;

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

-- Copy existing primary images into gallery table
INSERT INTO `product_image` (`product_id`, `path`, `sort_order`, `is_primary`)
SELECT p.`id`, p.`path`, 0, 1
FROM `product` p
WHERE NOT EXISTS (
    SELECT 1 FROM `product_image` pi WHERE pi.`product_id` = p.`id`
);
