ALTER TABLE `bosch_ecus`
    DROP COLUMN IF EXISTS `part_number`,
    DROP COLUMN IF EXISTS `vehicle_brand`,
    DROP COLUMN IF EXISTS `vehicle_model`,
    DROP COLUMN IF EXISTS `engine_type`,
    DROP COLUMN IF EXISTS `notes`,
    MODIFY COLUMN `ecu_number` VARCHAR(50) NOT NULL COMMENT 'Bosch ECU kodu (örn: 0281014238)',
    ADD COLUMN IF NOT EXISTS `ecu_type` VARCHAR(100) NOT NULL COMMENT 'ECU tipi (örn: EDC17CP02)' AFTER `ecu_number`,
    ADD UNIQUE KEY IF NOT EXISTS `uk_be_ecu_number` (`ecu_number`);
