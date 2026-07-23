CREATE TABLE IF NOT EXISTS `stage_service_pricing` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `stage_id` INT UNSIGNED NOT NULL,
    `service_package_id` INT UNSIGNED NOT NULL,
    `credit_cost` INT NOT NULL DEFAULT 0,
    `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY `uk_stage_service` (`stage_id`, `service_package_id`),
    CONSTRAINT `fk_ssp_stage` FOREIGN KEY (`stage_id`) REFERENCES `stages`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ssp_service` FOREIGN KEY (`service_package_id`) REFERENCES `service_packages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
