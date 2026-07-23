CREATE TABLE IF NOT EXISTS `brands` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    UNIQUE KEY `uk_brands_slug` (`slug`),
    INDEX `idx_brands_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vehicle_models` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `brand_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    UNIQUE KEY `uk_models_slug` (`slug`),
    INDEX `idx_models_brand` (`brand_id`),
    CONSTRAINT `fk_models_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `generations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `model_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    INDEX `idx_generations_model` (`model_id`),
    CONSTRAINT `fk_generations_model` FOREIGN KEY (`model_id`) REFERENCES `vehicle_models`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `engines` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `generation_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `displacement` VARCHAR(20) DEFAULT NULL,
    `fuel_type` VARCHAR(30) DEFAULT NULL,
    `horsepower` INT DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    INDEX `idx_engines_generation` (`generation_id`),
    CONSTRAINT `fk_engines_generation` FOREIGN KEY (`generation_id`) REFERENCES `generations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ecus` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `brand` VARCHAR(100) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    INDEX `idx_ecus_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reading_methods` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
