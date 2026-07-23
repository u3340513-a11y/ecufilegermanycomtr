CREATE TABLE IF NOT EXISTS `transmission_types` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transmission_types` (`name`, `is_active`, `sort_order`) VALUES
('Manuel', 1, 1),
('Otomatik', 1, 2),
('Yarı Otomatik (DSG/DCT)', 1, 3),
('CVT', 1, 4),
('Robotik (AMT)', 1, 5);
