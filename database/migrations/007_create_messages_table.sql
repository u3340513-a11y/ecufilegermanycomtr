CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `request_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `attachment_path` VARCHAR(500) DEFAULT NULL,
    `attachment_name` VARCHAR(255) DEFAULT NULL,
    `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_messages_request` (`request_id`),
    INDEX `idx_messages_user` (`user_id`),
    INDEX `idx_messages_read` (`is_read`),
    CONSTRAINT `fk_messages_request` FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
