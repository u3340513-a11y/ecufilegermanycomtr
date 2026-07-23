-- ============================================================
-- YENİ SUNUCU İÇİN TAM VERİTABANI ŞEMASI
-- Kullanıcı  : admin_ecufile
-- Veritabanı : admin_ecufile
-- Oluşturma  : 2026-07-21
-- ============================================================

-- Veritabanı ve kullanıcı oluşturma (root ile çalıştır)
-- CREATE DATABASE IF NOT EXISTS `admin_ecufile`
--   CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- CREATE USER IF NOT EXISTS 'admin_ecufile'@'localhost' IDENTIFIED BY 'Zindan.11';
-- GRANT ALL PRIVILEGES ON `admin_ecufile`.* TO 'admin_ecufile'@'localhost';
-- FLUSH PRIVILEGES;

-- Aşağısını doğrudan admin_ecufile veritabanında çalıştır:
-- mysql -u admin_ecufile -p'Zindan.11' admin_ecufile < full_schema_new_server.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- ============================================================
-- 1. KULLANICILAR
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`           VARCHAR(100)  NOT NULL,
    `email`          VARCHAR(191)  NOT NULL,
    `password`       VARCHAR(255)  NOT NULL,
    `phone`          VARCHAR(20)   DEFAULT NULL,
    `company`        VARCHAR(150)  DEFAULT NULL,
    `avatar`         VARCHAR(255)  DEFAULT NULL,
    `credit_balance` INT           NOT NULL DEFAULT 0,
    `role`           ENUM('user','admin') NOT NULL DEFAULT 'user',
    `email_token`    VARCHAR(64)   DEFAULT NULL,
    `email_verified` TINYINT(1)   NOT NULL DEFAULT 0,
    `reset_token`    VARCHAR(64)   DEFAULT NULL,
    `reset_expires`  DATETIME      DEFAULT NULL,
    `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_users_email`       (`email`),
    INDEX `idx_users_role`            (`role`),
    INDEX `idx_users_reset_token`     (`reset_token`),
    INDEX `idx_users_email_token`     (`email_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. ARAÇ HİYERARŞİSİ
-- ============================================================
CREATE TABLE IF NOT EXISTS `brands` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100) NOT NULL,
    `slug`       VARCHAR(120) NOT NULL,
    `is_active`  TINYINT(1)  NOT NULL DEFAULT 1,
    `sort_order` INT          NOT NULL DEFAULT 0,
    UNIQUE KEY `uk_brands_slug`  (`slug`),
    INDEX `idx_brands_active`    (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vehicle_models` (
    `id`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `brand_id`  INT UNSIGNED NOT NULL,
    `name`      VARCHAR(100) NOT NULL,
    `slug`      VARCHAR(120) NOT NULL,
    `is_active` TINYINT(1)  NOT NULL DEFAULT 1,
    UNIQUE KEY `uk_models_slug` (`slug`),
    INDEX `idx_models_brand`    (`brand_id`),
    CONSTRAINT `fk_models_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `generations` (
    `id`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `model_id`  INT UNSIGNED NOT NULL,
    `name`      VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1)  NOT NULL DEFAULT 1,
    INDEX `idx_generations_model` (`model_id`),
    CONSTRAINT `fk_generations_model` FOREIGN KEY (`model_id`) REFERENCES `vehicle_models`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `engines` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `generation_id` INT UNSIGNED NOT NULL,
    `name`          VARCHAR(150) NOT NULL,
    `displacement`  VARCHAR(20)  DEFAULT NULL,
    `fuel_type`     VARCHAR(30)  DEFAULT NULL,
    `horsepower`    INT          DEFAULT NULL,
    `is_active`     TINYINT(1)  NOT NULL DEFAULT 1,
    INDEX `idx_engines_generation` (`generation_id`),
    CONSTRAINT `fk_engines_generation` FOREIGN KEY (`generation_id`) REFERENCES `generations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ecus` (
    `id`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`      VARCHAR(150) NOT NULL,
    `brand`     VARCHAR(100) DEFAULT NULL,
    `is_active` TINYINT(1)  NOT NULL DEFAULT 1,
    INDEX `idx_ecus_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reading_methods` (
    `id`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`      VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1)  NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `engine_ecus` (
    `id`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `engine_id` INT UNSIGNED NOT NULL,
    `ecu_id`    INT UNSIGNED NOT NULL,
    UNIQUE KEY `uk_engine_ecu` (`engine_id`, `ecu_id`),
    INDEX `idx_ee_engine` (`engine_id`),
    INDEX `idx_ee_ecu`    (`ecu_id`),
    CONSTRAINT `fk_ee_engine` FOREIGN KEY (`engine_id`) REFERENCES `engines`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ee_ecu`    FOREIGN KEY (`ecu_id`)    REFERENCES `ecus`(`id`)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. SERVİS PAKETLERİ
-- ============================================================
CREATE TABLE IF NOT EXISTS `service_packages` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(100) NOT NULL,
    `slug`        VARCHAR(120) NOT NULL,
    `credit_cost` INT          NOT NULL DEFAULT 0,
    `description` TEXT         DEFAULT NULL,
    `is_active`   TINYINT(1)  NOT NULL DEFAULT 1,
    `sort_order`  INT          NOT NULL DEFAULT 0,
    UNIQUE KEY `uk_service_packages_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. STAGE'LER (Performans Seviyeleri)
-- ============================================================
CREATE TABLE IF NOT EXISTS `stages` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(50)  NOT NULL,
    `slug`          VARCHAR(50)  NOT NULL,
    `base_credit`   INT          NOT NULL DEFAULT 0,
    `show_services` TINYINT(1)  NOT NULL DEFAULT 1,
    `is_active`     TINYINT(1)  NOT NULL DEFAULT 1,
    `sort_order`    INT          NOT NULL DEFAULT 0,
    UNIQUE KEY `uk_stages_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stage_service_pricing` (
    `id`                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `stage_id`           INT UNSIGNED NOT NULL,
    `service_package_id` INT UNSIGNED NOT NULL,
    `credit_cost`        INT          NOT NULL DEFAULT 0,
    `is_visible`         TINYINT(1)  NOT NULL DEFAULT 1,
    UNIQUE KEY `uk_stage_service` (`stage_id`, `service_package_id`),
    CONSTRAINT `fk_ssp_stage`   FOREIGN KEY (`stage_id`)           REFERENCES `stages`(`id`)           ON DELETE CASCADE,
    CONSTRAINT `fk_ssp_service` FOREIGN KEY (`service_package_id`) REFERENCES `service_packages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. VİTES TİPLERİ
-- ============================================================
CREATE TABLE IF NOT EXISTS `transmission_types` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100) NOT NULL,
    `is_active`  TINYINT(1)  NOT NULL DEFAULT 1,
    `sort_order` INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. TALEPLER (Requests)
-- ============================================================
CREATE TABLE IF NOT EXISTS `requests` (
    `id`                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`             INT UNSIGNED NOT NULL,
    `ticket_no`           VARCHAR(20)  NOT NULL,
    `brand_id`            INT UNSIGNED DEFAULT NULL,
    `model_id`            INT UNSIGNED DEFAULT NULL,
    `generation_id`       INT UNSIGNED DEFAULT NULL,
    `engine_id`           INT UNSIGNED DEFAULT NULL,
    `ecu_id`              INT UNSIGNED DEFAULT NULL,
    `stage_id`            INT UNSIGNED DEFAULT NULL,
    `transmission_type_id` INT UNSIGNED DEFAULT NULL,
    `year`                SMALLINT UNSIGNED DEFAULT NULL,
    `ecu_sw`              VARCHAR(100) DEFAULT NULL,
    `ecu_hw`              VARCHAR(100) DEFAULT NULL,
    `reading_method_id`   INT UNSIGNED DEFAULT NULL,
    `plate_number`        VARCHAR(20)  DEFAULT NULL,
    `customer_note`       TEXT         DEFAULT NULL,
    `status`              ENUM('pending','reviewing','processing','revision','completed','cancelled') NOT NULL DEFAULT 'pending',
    `total_credits`       INT          NOT NULL DEFAULT 0,
    `assigned_admin_id`   INT UNSIGNED DEFAULT NULL,
    `created_at`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_requests_ticket`  (`ticket_no`),
    INDEX `idx_requests_user`        (`user_id`),
    INDEX `idx_requests_status`      (`status`),
    INDEX `idx_requests_created`     (`created_at`),
    CONSTRAINT `fk_requests_user`           FOREIGN KEY (`user_id`)             REFERENCES `users`(`id`)           ON DELETE CASCADE,
    CONSTRAINT `fk_requests_brand`          FOREIGN KEY (`brand_id`)            REFERENCES `brands`(`id`)          ON DELETE SET NULL,
    CONSTRAINT `fk_requests_model`          FOREIGN KEY (`model_id`)            REFERENCES `vehicle_models`(`id`)  ON DELETE SET NULL,
    CONSTRAINT `fk_requests_generation`     FOREIGN KEY (`generation_id`)       REFERENCES `generations`(`id`)     ON DELETE SET NULL,
    CONSTRAINT `fk_requests_engine`         FOREIGN KEY (`engine_id`)           REFERENCES `engines`(`id`)         ON DELETE SET NULL,
    CONSTRAINT `fk_requests_ecu`            FOREIGN KEY (`ecu_id`)              REFERENCES `ecus`(`id`)            ON DELETE SET NULL,
    CONSTRAINT `fk_requests_stage`          FOREIGN KEY (`stage_id`)            REFERENCES `stages`(`id`)          ON DELETE SET NULL,
    CONSTRAINT `fk_requests_reading_method` FOREIGN KEY (`reading_method_id`)   REFERENCES `reading_methods`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_requests_admin`          FOREIGN KEY (`assigned_admin_id`)   REFERENCES `users`(`id`)           ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. TALEP SERVİSLERİ
-- ============================================================
CREATE TABLE IF NOT EXISTS `request_services` (
    `id`                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `request_id`         INT UNSIGNED NOT NULL,
    `service_package_id` INT UNSIGNED NOT NULL,
    `credit_cost`        INT          NOT NULL DEFAULT 0,
    INDEX `idx_rs_request` (`request_id`),
    INDEX `idx_rs_service` (`service_package_id`),
    CONSTRAINT `fk_rs_request` FOREIGN KEY (`request_id`)         REFERENCES `requests`(`id`)         ON DELETE CASCADE,
    CONSTRAINT `fk_rs_service` FOREIGN KEY (`service_package_id`) REFERENCES `service_packages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. DOSYALAR
-- ============================================================
CREATE TABLE IF NOT EXISTS `files` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `request_id`    INT UNSIGNED NOT NULL,
    `user_id`       INT UNSIGNED NOT NULL,
    `filename`      VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `path`          VARCHAR(500) NOT NULL,
    `size`          INT UNSIGNED NOT NULL DEFAULT 0,
    `mime_type`     VARCHAR(100) DEFAULT NULL,
    `type`          ENUM('original','revision','completed') NOT NULL DEFAULT 'original',
    `version`       INT          NOT NULL DEFAULT 1,
    `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_files_request` (`request_id`),
    INDEX `idx_files_user`    (`user_id`),
    INDEX `idx_files_type`    (`type`),
    CONSTRAINT `fk_files_request` FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_files_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. MESAJLAR
-- ============================================================
CREATE TABLE IF NOT EXISTS `messages` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `request_id`      INT UNSIGNED NOT NULL,
    `user_id`         INT UNSIGNED NOT NULL,
    `content`         TEXT         NOT NULL,
    `attachment_path` VARCHAR(500) DEFAULT NULL,
    `attachment_name` VARCHAR(255) DEFAULT NULL,
    `is_admin`        TINYINT(1)  NOT NULL DEFAULT 0,
    `is_read`         TINYINT(1)  NOT NULL DEFAULT 0,
    `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_messages_request` (`request_id`),
    INDEX `idx_messages_user`    (`user_id`),
    INDEX `idx_messages_read`    (`is_read`),
    CONSTRAINT `fk_messages_request` FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_messages_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. KREDİ İŞLEMLERİ
-- ============================================================
CREATE TABLE IF NOT EXISTS `credit_transactions` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`       INT UNSIGNED NOT NULL,
    `request_id`    INT UNSIGNED DEFAULT NULL,
    `type`          ENUM('purchase','usage','refund','admin_add') NOT NULL,
    `amount`        INT          NOT NULL,
    `balance_after` INT          NOT NULL,
    `description`   VARCHAR(255) DEFAULT NULL,
    `admin_id`      INT UNSIGNED DEFAULT NULL,
    `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_ct_user`    (`user_id`),
    INDEX `idx_ct_type`    (`type`),
    INDEX `idx_ct_created` (`created_at`),
    CONSTRAINT `fk_ct_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE,
    CONSTRAINT `fk_ct_request` FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_ct_admin`   FOREIGN KEY (`admin_id`)   REFERENCES `users`(`id`)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. ÖDEME LİNKLERİ
-- ============================================================
CREATE TABLE IF NOT EXISTS `payment_links` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`       INT UNSIGNED NOT NULL,
    `stripe_link`   VARCHAR(500) NOT NULL,
    `credit_amount` INT          NOT NULL,
    `price`         DECIMAL(10,2) NOT NULL,
    `currency`      VARCHAR(3)   NOT NULL DEFAULT 'EUR',
    `status`        ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
    `approved_by`   INT UNSIGNED DEFAULT NULL,
    `approved_at`   DATETIME     DEFAULT NULL,
    `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_pl_user`   (`user_id`),
    INDEX `idx_pl_status` (`status`),
    CONSTRAINT `fk_pl_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pl_approved` FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. ARIZA KODLARI (OBD-II)
-- ============================================================
CREATE TABLE IF NOT EXISTS `fault_codes` (
    `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code`             VARCHAR(20)  NOT NULL,
    `title`            VARCHAR(255) NOT NULL,
    `description`      TEXT         DEFAULT NULL,
    `solution`         TEXT         DEFAULT NULL,
    `meta_title`       VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(500) DEFAULT NULL,
    `slug`             VARCHAR(255) NOT NULL,
    `is_published`     TINYINT(1)  NOT NULL DEFAULT 0,
    `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_fault_codes_code` (`code`),
    UNIQUE KEY `uk_fault_codes_slug` (`slug`),
    INDEX `idx_fc_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 13. BOSCH ECU KODLARI
-- ============================================================
CREATE TABLE IF NOT EXISTS `bosch_ecus` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ecu_number` VARCHAR(50)  NOT NULL COMMENT 'Bosch ECU kodu (örn: 0281014238)',
    `ecu_type`   VARCHAR(100) NOT NULL COMMENT 'ECU tipi (örn: EDC17CP02)',
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_be_ecu_number`   (`ecu_number`),
    INDEX `idx_be_ecu_number`       (`ecu_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 14. BOSCH DF ARIZA KODLARI
-- ============================================================
CREATE TABLE IF NOT EXISTS `df_fault_codes` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `df_code`     VARCHAR(20)  NOT NULL COMMENT 'Bosch DF kodu (örn: DF010)',
    `p_code`      VARCHAR(20)  NOT NULL COMMENT 'OBD-II P kodu (örn: P0409)',
    `description` TEXT         DEFAULT NULL COMMENT 'Açıklama',
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_dfc_df_code`  (`df_code`),
    INDEX `idx_dfc_p_code`       (`p_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Bosch DF kodu → OBD-II P kodu eşleme tablosu';

-- ============================================================
-- 15. BİLDİRİMLER
-- ============================================================
CREATE TABLE IF NOT EXISTS `notifications` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED NOT NULL,
    `title`      VARCHAR(255) NOT NULL,
    `content`    TEXT         DEFAULT NULL,
    `type`       VARCHAR(50)  NOT NULL DEFAULT 'info',
    `link`       VARCHAR(500) DEFAULT NULL,
    `is_read`    TINYINT(1)  NOT NULL DEFAULT 0,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_notif_user`    (`user_id`),
    INDEX `idx_notif_read`    (`is_read`),
    INDEX `idx_notif_created` (`created_at`),
    CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 16. AYARLAR
-- ============================================================
CREATE TABLE IF NOT EXISTS `settings` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key_name`   VARCHAR(100) NOT NULL,
    `value`      TEXT         DEFAULT NULL,
    `group_name` VARCHAR(50)  NOT NULL DEFAULT 'general',
    UNIQUE KEY `uk_settings_key`   (`key_name`),
    INDEX `idx_settings_group`     (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 17. AKTİVİTE LOGLARI
-- ============================================================
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`     INT UNSIGNED DEFAULT NULL,
    `action`      VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50)  DEFAULT NULL,
    `entity_id`   INT UNSIGNED DEFAULT NULL,
    `details`     TEXT         DEFAULT NULL,
    `ip_address`  VARCHAR(45)  DEFAULT NULL,
    `user_agent`  VARCHAR(500) DEFAULT NULL,
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_al_user`    (`user_id`),
    INDEX `idx_al_action`  (`action`),
    INDEX `idx_al_entity`  (`entity_type`, `entity_id`),
    INDEX `idx_al_created` (`created_at`),
    CONSTRAINT `fk_al_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 18. RATE LIMIT
-- ============================================================
CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `identifier`   VARCHAR(100) NOT NULL,
    `action`       VARCHAR(50)  NOT NULL,
    `attempts`     INT          NOT NULL DEFAULT 1,
    `window_start` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_rate_limits` (`identifier`, `action`),
    INDEX `idx_rl_window`       (`window_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- FOREIGN KEY CHECK TEKRAR AÇ
-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- ============================================================
-- ZORUNLU SEED VERİLERİ
-- (Sistem çalışması için bu verilerin olması gerekir)
-- ============================================================
-- ============================================================

-- ----------------------------------------------------------------
-- VİTES TİPLERİ
-- ----------------------------------------------------------------
INSERT INTO `transmission_types` (`name`, `is_active`, `sort_order`) VALUES
('Manuel', 1, 1),
('Otomatik', 1, 2),
('Yarı Otomatik (DSG/DCT)', 1, 3),
('CVT', 1, 4),
('Robotik (AMT)', 1, 5);

-- ----------------------------------------------------------------
-- SERVİS PAKETLERİ
-- ----------------------------------------------------------------
SET foreign_key_checks = 0;
DELETE FROM `stage_service_pricing`;
DELETE FROM `request_services`;
DELETE FROM `service_packages`;
ALTER TABLE `service_packages` AUTO_INCREMENT = 1;
SET foreign_key_checks = 1;

INSERT INTO `service_packages` (`id`, `name`, `slug`, `credit_cost`, `description`, `is_active`, `sort_order`) VALUES
(1,  'DTC OFF',                           'dtc-off',              4,  'DTC arıza kodu silme',                1, 1),
(2,  'DPF OFF',                           'dpf-off',              8,  'DPF filtre iptali',                   1, 2),
(3,  'EGR OFF',                           'egr-off',              6,  'EGR valf iptali',                     1, 3),
(4,  'ADBLUE OFF',                        'adblue-off',           9,  'AdBlue sistem iptali',                1, 4),
(5,  'DPF+EGR OFF',                       'dpf-egr-off',          9,  'DPF + EGR birlikte iptal',            1, 5),
(6,  'ADBLUE+DPF+EGR OFF',               'adblue-dpf-egr-off',  16,  'AdBlue + DPF + EGR birlikte iptal',  1, 6),
(7,  'ADBLUE+DPF',                        'adblue-dpf',           4,  'AdBlue + DPF birlikte iptal',         1, 7),
(8,  'Decat (CAT OFF)',                    'decat-cat-off',        6,  'Katalitik konvertör iptali',          1, 8),
(9,  'O2/NOX/Lambda (If Possible)',        'o2-nox-lambda',        6,  'O2/NOX/Lambda sensör iptali',         1, 9),
(10, 'MAF OFF (If Possible)',              'maf-off',              4,  'MAF sensör iptali',                   1, 10),
(11, 'Additive',                           'additive',             3,  'Additive sistem iptali',              1, 11),
(12, 'VMAX OFF (If Possible)',             'vmax-off',             5,  'Hız limiti kaldırma',                 1, 12),
(13, 'EBS Flex-Fuel (If Possible)',        'ebs-flex-fuel',       10,  'EBS Flex-Fuel ayarı',                 1, 13),
(14, 'Hot Start/Cold Start (If Possible)', 'hot-cold-start',       8,  'Hot Start/Cold Start düzeltme',       1, 14),
(15, 'FLAP OFF (If Possible)',             'flap-off',             7,  'Egzoz klape iptali',                  1, 15),
(16, 'Water Pump OFF (If Possible)',       'water-pump-off',       5,  'Su pompası iptali',                   1, 16),
(17, 'Start & Stop OFF (If Possible)',     'start-stop-off',      11,  'Start & Stop iptali',                 1, 17),
(18, 'Pop & Bang (If Possible)',           'pop-bang',             8,  'Pop & Bang exhaust tune',             1, 18),
(19, 'Hard Cut Limiter (If Possible)',     'hard-cut-limiter',     8,  'Hardcut limiter',                     1, 19),
(20, 'Launch Control (If Possible)',       'launch-control',      10,  'Launch Control aktivasyonu',          1, 20),
(21, 'Special Request',                    'special-request',      5,  'Özel istek',                          1, 21),
(22, 'FILE CHECK',                         'file-check',           4,  'Dosya kontrol',                       1, 22),
(23, 'CHECKSUM (If Possible)',             'checksum',             3,  'Checksum düzeltme',                   1, 23),
(24, 'TVA OFF (If Possible)',              'tva-off',              5,  'TVA iptali',                          1, 24),
(25, 'DPF OFF (If Possible)',              'dpf-off-possible',    12,  'DPF OFF (mümkünse)',                  1, 25),
(26, 'Thermostat fix (If Possible)',       'thermostat-fix',      16,  'Termostat düzeltme',                  1, 26),
(27, 'File Expertise (If Possible)',       'file-expertise',      17,  'Dosya uzmanlık analizi',              1, 27),
(28, 'DSG optimization',                   'dsg-optimization',    11,  'DSG şanzıman optimizasyonu',          1, 28),
(29, 'Transmission Stage 1 Performance',  'trans-stage1-perf',   20,  'Şanzıman Stage 1 Performans',         1, 29),
(30, 'Transmission Stage 2 Performance',  'trans-stage2-perf',   30,  'Şanzıman Stage 2 Performans',         1, 30);

-- ----------------------------------------------------------------
-- STAGE'LER
-- ----------------------------------------------------------------
INSERT INTO `stages` (`id`, `name`, `slug`, `base_credit`, `show_services`, `is_active`, `sort_order`) VALUES
(1, 'Only Options',  'only-options',   0,  1, 1, 1),
(2, 'Stage 1',       'stage-1',        10, 1, 1, 2),
(3, 'Stage 2',       'stage-2',        15, 1, 1, 3),
(4, 'Stage 3',       'stage-3',        30, 1, 1, 4),
(5, 'Original File', 'original-file',  4,  0, 1, 5),
(6, 'More Options',  'more-options',   0,  2, 1, 6);

-- ----------------------------------------------------------------
-- STAGE - SERVİS FİYATLANDIRMASI
-- ----------------------------------------------------------------
INSERT INTO `stage_service_pricing` (`stage_id`, `service_package_id`, `credit_cost`, `is_visible`) VALUES
-- Only Options (stage_id=1)
(1,1,4,1),(1,2,8,1),(1,3,6,1),(1,4,9,1),(1,5,9,1),(1,6,16,1),(1,7,4,1),
(1,8,6,1),(1,9,6,1),(1,10,4,1),(1,11,3,1),(1,12,5,1),(1,13,10,1),(1,14,8,1),
(1,15,7,1),(1,16,5,1),(1,17,11,1),(1,18,8,1),(1,19,8,1),(1,20,10,1),(1,21,5,1),
(1,22,4,1),(1,23,3,1),(1,24,5,1),(1,25,12,1),(1,26,16,1),(1,27,17,1),
-- Stage 1 (stage_id=2)
(2,1,2,1),(2,2,3,1),(2,3,3,1),(2,4,7,1),(2,5,5,1),(2,6,10,1),(2,7,8,1),
(2,8,3,1),(2,9,3,1),(2,10,4,1),(2,11,3,1),(2,12,3,1),(2,13,3,1),(2,14,4,1),
(2,15,5,1),(2,16,4,1),(2,17,10,1),(2,18,7,1),(2,19,7,1),(2,20,8,1),(2,21,6,1),
(2,22,3,1),(2,23,6,1),(2,24,4,1),(2,25,10,1),(2,26,14,1),
-- Stage 2 (stage_id=3)
(3,1,1,1),(3,2,2,1),(3,3,2,1),(3,4,5,1),(3,5,4,1),(3,6,8,1),(3,7,6,1),
(3,8,2,1),(3,9,2,1),(3,10,3,1),(3,11,2,1),(3,12,2,1),(3,13,2,1),(3,14,3,1),
(3,15,4,1),(3,16,3,1),(3,17,9,1),(3,18,5,1),(3,19,5,1),(3,20,7,1),(3,21,5,1),
(3,22,3,1),(3,23,5,1),(3,24,3,1),(3,25,9,1),(3,26,12,1),
-- Stage 3 (stage_id=4)
(4,1,1,1),(4,2,1,1),(4,3,1,1),(4,4,4,1),(4,5,3,1),(4,6,6,1),(4,7,5,1),
(4,8,1,1),(4,9,1,1),(4,10,2,1),(4,11,1,1),(4,12,1,1),(4,13,1,1),(4,14,2,1),
(4,15,3,1),(4,16,2,1),(4,17,8,1),(4,18,4,1),(4,19,4,1),(4,20,5,1),(4,21,4,1),
(4,22,2,1),(4,23,4,1),(4,24,2,1),(4,25,8,1),(4,26,10,1),
-- More Options (stage_id=6)
(6,28,11,1),(6,29,20,1),(6,30,30,1);

-- ----------------------------------------------------------------
-- AYARLAR (Varsayılan)
-- ----------------------------------------------------------------
INSERT INTO `settings` (`key_name`, `value`, `group_name`) VALUES
('site_name',           'ECU Dosya Servis', 'general'),
('site_email',          'info@example.com', 'general'),
('site_phone',          '',                 'general'),
('site_address',        '',                 'general'),
('maintenance_mode',    '0',                'general'),
('default_credits',     '0',               'general'),
('min_credit_purchase', '10',              'credits'),
('credit_price',        '5.00',            'credits');

-- ----------------------------------------------------------------
-- ADMIN KULLANICISI
-- Şifre: admin123  (bcrypt hash)
-- Giriş yaptıktan sonra admin panelinden değiştirin!
-- ----------------------------------------------------------------
INSERT INTO `users` (`name`, `email`, `password`, `role`, `email_verified`, `is_active`, `credit_balance`) VALUES
('Admin', 'admin@ecuplatform.com', '$2y$12$LJ3m4yv9xSRLqNRBGF6Q4OJpKzRQTQ8aEVXmWJjKvQxqR5GdFzKaG', 'admin', 1, 1, 0);

-- ============================================================
-- NOT: Büyük seed verileri (ecus, reading_methods, brands,
-- models, generations, engines, fault_codes, bosch_ecus,
-- df_fault_codes) için mevcut sunucudan mysqldump alın:
--
-- mysqldump -u eskikullanici -p eskidb \
--   ecus reading_methods brands vehicle_models generations \
--   engines engine_ecus fault_codes bosch_ecus df_fault_codes \
--   > seed_data.sql
--
-- Sonra yeni sunucuda:
-- mysql -u admin_ecufile -p'Zindan.11' admin_ecufile < seed_data.sql
-- ============================================================
