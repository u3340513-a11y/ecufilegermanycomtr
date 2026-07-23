CREATE TABLE IF NOT EXISTS `engine_ecus` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `engine_id` INT UNSIGNED NOT NULL,
    `ecu_id` INT UNSIGNED NOT NULL,
    UNIQUE KEY `uk_engine_ecu` (`engine_id`, `ecu_id`),
    INDEX `idx_ee_engine` (`engine_id`),
    INDEX `idx_ee_ecu` (`ecu_id`),
    CONSTRAINT `fk_ee_engine` FOREIGN KEY (`engine_id`) REFERENCES `engines`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ee_ecu` FOREIGN KEY (`ecu_id`) REFERENCES `ecus`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
