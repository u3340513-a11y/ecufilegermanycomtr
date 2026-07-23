CREATE TABLE IF NOT EXISTS `credit_transactions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `request_id` INT UNSIGNED DEFAULT NULL,
    `type` ENUM('purchase','usage','refund','admin_add') NOT NULL,
    `amount` INT NOT NULL,
    `balance_after` INT NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `admin_id` INT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_ct_user` (`user_id`),
    INDEX `idx_ct_type` (`type`),
    INDEX `idx_ct_created` (`created_at`),
    CONSTRAINT `fk_ct_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ct_request` FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_ct_admin` FOREIGN KEY (`admin_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
