CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key_name` VARCHAR(100) NOT NULL,
    `value` TEXT DEFAULT NULL,
    `group_name` VARCHAR(50) NOT NULL DEFAULT 'general',
    UNIQUE KEY `uk_settings_key` (`key_name`),
    INDEX `idx_settings_group` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key_name`, `value`, `group_name`) VALUES
('site_name', 'ECU Dosya Servis', 'general'),
('site_email', 'info@example.com', 'general'),
('site_phone', '', 'general'),
('site_address', '', 'general'),
('maintenance_mode', '0', 'general'),
('default_credits', '0', 'general'),
('min_credit_purchase', '10', 'credits'),
('credit_price', '5.00', 'credits');
