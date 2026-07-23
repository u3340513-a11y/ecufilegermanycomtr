CREATE TABLE IF NOT EXISTS `df_fault_codes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `df_code`     VARCHAR(20)  NOT NULL COMMENT 'Bosch DF kodu (örn: DF010)',
    `p_code`      VARCHAR(20)  NOT NULL COMMENT 'OBD-II P kodu (örn: P0409)',
    `description` TEXT         DEFAULT NULL COMMENT 'Açıklama (Fransızca veya Türkçe)',
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_dfc_df_code`  (`df_code`),
    INDEX         `idx_dfc_p_code` (`p_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Bosch DF kodu → OBD-II P kodu eşleme tablosu';
