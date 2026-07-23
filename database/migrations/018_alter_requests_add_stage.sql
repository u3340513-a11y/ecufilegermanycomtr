ALTER TABLE `requests` ADD COLUMN `stage_id` INT UNSIGNED DEFAULT NULL AFTER `ecu_id`;
ALTER TABLE `requests` ADD COLUMN `transmission_type_id` INT UNSIGNED DEFAULT NULL AFTER `stage_id`;
ALTER TABLE `requests` ADD CONSTRAINT `fk_requests_stage` FOREIGN KEY (`stage_id`) REFERENCES `stages`(`id`) ON DELETE SET NULL;
