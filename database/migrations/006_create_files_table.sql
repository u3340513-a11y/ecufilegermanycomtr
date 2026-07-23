CREATE TABLE IF NOT EXISTS `files` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `request_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(500) NOT NULL,
    `size` INT UNSIGNED NOT NULL DEFAULT 0,
    `mime_type` VARCHAR(100) DEFAULT NULL,
    `type` ENUM('original','revision','completed') NOT NULL DEFAULT 'original',
    `version` INT NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_files_request` (`request_id`),
    INDEX `idx_files_user` (`user_id`),
    INDEX `idx_files_type` (`type`),
    CONSTRAINT `fk_files_request` FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_files_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
