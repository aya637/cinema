-- Create table `moviehomepage` in the `onlinebooking` database.
-- This table stores movie/poster records used by the homepage carousel.
CREATE TABLE IF NOT EXISTS `moviehomepage` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `link` VARCHAR(255) DEFAULT NULL,
  `genre` VARCHAR(128) DEFAULT NULL,
  `duration` VARCHAR(64) DEFAULT NULL,
  `rating` DECIMAL(3,1) DEFAULT NULL,
  `emoji` VARCHAR(8) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `status` VARCHAR(32) DEFAULT 'now_showing',
  `position` INT DEFAULT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`active`),
  INDEX (`position`),
  INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
