SET foreign_key_checks = 0;
DELETE FROM `stage_service_pricing`;
DELETE FROM `request_services`;
DELETE FROM `service_packages`;
ALTER TABLE `service_packages` AUTO_INCREMENT = 1;
SET foreign_key_checks = 1;

INSERT INTO `service_packages` (`id`, `name`, `slug`, `credit_cost`, `description`, `is_active`, `sort_order`) VALUES
(1,  'DTC OFF',                          'dtc-off',              4, 'DTC arıza kodu silme',                    1, 1),
(2,  'DPF OFF',                          'dpf-off',              8, 'DPF filtre iptali',                       1, 2),
(3,  'EGR OFF',                          'egr-off',              6, 'EGR valf iptali',                         1, 3),
(4,  'ADBLUE OFF',                       'adblue-off',           9, 'AdBlue sistem iptali',                    1, 4),
(5,  'DPF+EGR OFF',                      'dpf-egr-off',          9, 'DPF + EGR birlikte iptal',                1, 5),
(6,  'ADBLUE+DPF+EGR OFF',              'adblue-dpf-egr-off',  16, 'AdBlue + DPF + EGR birlikte iptal',       1, 6),
(7,  'ADBLUE+DPF',                       'adblue-dpf',           4, 'AdBlue + DPF birlikte iptal',             1, 7),
(8,  'Decat (CAT OFF)',                   'decat-cat-off',        6, 'Katalitik konvertör iptali',              1, 8),
(9,  'O2/NOX/Lambda (If Possible)',       'o2-nox-lambda',        6, 'O2/NOX/Lambda sensör iptali',             1, 9),
(10, 'MAF OFF (If Possible)',             'maf-off',              4, 'MAF sensör iptali',                       1, 10),
(11, 'Additive',                          'additive',             3, 'Additive sistem iptali',                  1, 11),
(12, 'VMAX OFF (If Possible)',            'vmax-off',             5, 'Hız limiti kaldırma',                     1, 12),
(13, 'EBS Flex-Fuel (If Possible)',       'ebs-flex-fuel',       10, 'EBS Flex-Fuel ayarı',                     1, 13),
(14, 'Hot Start/Cold Start (If Possible)','hot-cold-start',       8, 'Hot Start/Cold Start düzeltme',           1, 14),
(15, 'FLAP OFF (If Possible)',            'flap-off',             7, 'Egzoz klape iptali',                      1, 15),
(16, 'Water Pump OFF (If Possible)',      'water-pump-off',       5, 'Su pompası iptali',                       1, 16),
(17, 'Start & Stop OFF (If Possible)',    'start-stop-off',      11, 'Start & Stop iptali',                     1, 17),
(18, 'Pop & Bang (If Possible)',          'pop-bang',             8, 'Pop & Bang exhaust tune',                 1, 18),
(19, 'Hard Cut Limiter (If Possible)',    'hard-cut-limiter',     8, 'Hardcut limiter',                         1, 19),
(20, 'Launch Control (If Possible)',      'launch-control',      10, 'Launch Control aktivasyonu',              1, 20),
(21, 'Special Request',                   'special-request',      5, 'Özel istek',                              1, 21),
(22, 'FILE CHECK',                        'file-check',           4, 'Dosya kontrol',                           1, 22),
(23, 'CHECKSUM (If Possible)',            'checksum',             3, 'Checksum düzeltme',                       1, 23),
(24, 'TVA OFF (If Possible)',             'tva-off',              5, 'TVA iptali',                              1, 24),
(25, 'DPF OFF (If Possible)',             'dpf-off-possible',    12, 'DPF OFF (mümkünse)',                      1, 25),
(26, 'Thermostat fix (If Possible)',      'thermostat-fix',      16, 'Termostat düzeltme',                      1, 26),
(27, 'File Expertise (If Possible)',      'file-expertise',      17, 'Dosya uzmanlık analizi',                  1, 27),
(28, 'DSG optimization',                  'dsg-optimization',    11, 'DSG şanzıman optimizasyonu',              1, 28),
(29, 'Transmission Stage 1 Performance', 'trans-stage1-perf',   20, 'Şanzıman Stage 1 Performans',             1, 29),
(30, 'Transmission Stage 2 Performance', 'trans-stage2-perf',   30, 'Şanzıman Stage 2 Performans',             1, 30);
