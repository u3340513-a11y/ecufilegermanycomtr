CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `identifier` VARCHAR(100) NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `attempts` INT NOT NULL DEFAULT 1,
    `window_start` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_rate_limits` (`identifier`, `action`),
    INDEX `idx_rl_window` (`window_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
