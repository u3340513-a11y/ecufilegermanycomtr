CREATE TABLE IF NOT EXISTS `request_services` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `request_id` INT UNSIGNED NOT NULL,
    `service_package_id` INT UNSIGNED NOT NULL,
    `credit_cost` INT NOT NULL DEFAULT 0,
    INDEX `idx_rs_request` (`request_id`),
    INDEX `idx_rs_service` (`service_package_id`),
    CONSTRAINT `fk_rs_request` FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_rs_service` FOREIGN KEY (`service_package_id`) REFERENCES `service_packages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
