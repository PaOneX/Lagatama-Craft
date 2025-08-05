-- Add Google sign-in and OTP expiry support
-- Run: mysql -u root -p lagatama_craft < database/migrations/002_auth_email.sql

ALTER TABLE `user`
    ADD COLUMN `verification_expires` DATETIME NULL AFTER `verification_code`,
    ADD COLUMN `google_id` VARCHAR(100) NULL AFTER `verification_expires`;

ALTER TABLE `user` ADD UNIQUE KEY `user_google_id` (`google_id`);
