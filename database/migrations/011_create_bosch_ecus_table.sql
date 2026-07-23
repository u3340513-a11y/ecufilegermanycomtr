CREATE TABLE IF NOT EXISTS `bosch_ecus` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ecu_number` VARCHAR(50) NOT NULL,
    `part_number` VARCHAR(50) DEFAULT NULL,
    `vehicle_brand` VARCHAR(100) DEFAULT NULL,
    `vehicle_model` VARCHAR(100) DEFAULT NULL,
    `engine_type` VARCHAR(100) DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_be_ecu_number` (`ecu_number`),
    INDEX `idx_be_part_number` (`part_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
