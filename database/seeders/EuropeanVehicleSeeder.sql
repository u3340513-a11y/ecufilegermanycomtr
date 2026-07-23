-- ============================================================
-- Avrupa Araç Markaları ve Modelleri Seed Verisi
-- ID bağımsız — slug ile subquery kullanır
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------
-- BRANDS (varsa atla)
-- ----------------------------------------
INSERT IGNORE INTO `brands` (`name`, `slug`, `is_active`, `sort_order`) VALUES
('Volkswagen',   'volkswagen',   1, 1),
('BMW',          'bmw',          1, 2),
('Mercedes-Benz','mercedes-benz',1, 3),
('Audi',         'audi',         1, 4),
('Opel',         'opel',         1, 5),
('Ford',         'ford',         1, 6),
('Renault',      'renault',      1, 7),
('Peugeot',      'peugeot',      1, 8),
('Citroën',      'citroen',      1, 9),
('Fiat',         'fiat',         1, 10),
('Alfa Romeo',   'alfa-romeo',   1, 11),
('Seat',         'seat',         1, 12),
('Skoda',        'skoda',        1, 13),
('Volvo',        'volvo',        1, 14),
('Porsche',      'porsche',      1, 15),
('Land Rover',   'land-rover',   1, 16),
('Jaguar',       'jaguar',       1, 17),
('MINI',         'mini',         1, 18),
('Dacia',        'dacia',        1, 19),
('Kia',          'kia',          1, 20),
('Hyundai',      'hyundai',      1, 21),
('Toyota',       'toyota',       1, 22),
('Honda',        'honda',        1, 23),
('Nissan',       'nissan',       1, 24),
('Mazda',        'mazda',        1, 25),
('Subaru',       'subaru',       1, 26),
('Mitsubishi',   'mitsubishi',   1, 27),
('Lancia',       'lancia',       1, 28),
('Saab',         'saab',         1, 29),
('Chevrolet',    'chevrolet',    1, 30);

-- ----------------------------------------
-- VEHICLE MODELS (slug'a göre brand_id çek)
-- ----------------------------------------

-- Volkswagen
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='volkswagen'), 'Golf',        'vw-golf',        1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Passat',      'vw-passat',      1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Polo',        'vw-polo',        1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Tiguan',      'vw-tiguan',      1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Touareg',     'vw-touareg',     1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'T-Roc',       'vw-t-roc',       1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Arteon',      'vw-arteon',      1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Sharan',      'vw-sharan',      1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Touran',      'vw-touran',      1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Caddy',       'vw-caddy',       1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Transporter', 'vw-transporter', 1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'Crafter',     'vw-crafter',     1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'ID.3',        'vw-id3',         1),
((SELECT id FROM brands WHERE slug='volkswagen'), 'ID.4',        'vw-id4',         1);

-- BMW
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='bmw'), '1 Serisi', 'bmw-1-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), '2 Serisi', 'bmw-2-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), '3 Serisi', 'bmw-3-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), '4 Serisi', 'bmw-4-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), '5 Serisi', 'bmw-5-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), '6 Serisi', 'bmw-6-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), '7 Serisi', 'bmw-7-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), '8 Serisi', 'bmw-8-serisi', 1),
((SELECT id FROM brands WHERE slug='bmw'), 'X1',       'bmw-x1',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'X2',       'bmw-x2',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'X3',       'bmw-x3',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'X4',       'bmw-x4',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'X5',       'bmw-x5',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'X6',       'bmw-x6',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'X7',       'bmw-x7',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'M3',       'bmw-m3',       1),
((SELECT id FROM brands WHERE slug='bmw'), 'M5',       'bmw-m5',       1);

-- Mercedes-Benz
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'A-Serisi',  'mb-a-serisi',  1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'B-Serisi',  'mb-b-serisi',  1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'C-Serisi',  'mb-c-serisi',  1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'E-Serisi',  'mb-e-serisi',  1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'S-Serisi',  'mb-s-serisi',  1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'GLA',       'mb-gla',       1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'GLB',       'mb-glb',       1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'GLC',       'mb-glc',       1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'GLE',       'mb-gle',       1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'GLS',       'mb-gls',       1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'CLA',       'mb-cla',       1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'CLS',       'mb-cls',       1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'AMG GT',    'mb-amg-gt',    1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'Vito',      'mb-vito',      1),
((SELECT id FROM brands WHERE slug='mercedes-benz'), 'Sprinter',  'mb-sprinter',  1);

-- Audi
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='audi'), 'A1',  'audi-a1',  1),
((SELECT id FROM brands WHERE slug='audi'), 'A3',  'audi-a3',  1),
((SELECT id FROM brands WHERE slug='audi'), 'A4',  'audi-a4',  1),
((SELECT id FROM brands WHERE slug='audi'), 'A5',  'audi-a5',  1),
((SELECT id FROM brands WHERE slug='audi'), 'A6',  'audi-a6',  1),
((SELECT id FROM brands WHERE slug='audi'), 'A7',  'audi-a7',  1),
((SELECT id FROM brands WHERE slug='audi'), 'A8',  'audi-a8',  1),
((SELECT id FROM brands WHERE slug='audi'), 'Q2',  'audi-q2',  1),
((SELECT id FROM brands WHERE slug='audi'), 'Q3',  'audi-q3',  1),
((SELECT id FROM brands WHERE slug='audi'), 'Q5',  'audi-q5',  1),
((SELECT id FROM brands WHERE slug='audi'), 'Q7',  'audi-q7',  1),
((SELECT id FROM brands WHERE slug='audi'), 'Q8',  'audi-q8',  1),
((SELECT id FROM brands WHERE slug='audi'), 'TT',  'audi-tt',  1),
((SELECT id FROM brands WHERE slug='audi'), 'R8',  'audi-r8',  1),
((SELECT id FROM brands WHERE slug='audi'), 'RS3', 'audi-rs3', 1),
((SELECT id FROM brands WHERE slug='audi'), 'RS6', 'audi-rs6', 1);

-- Opel
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='opel'), 'Astra',     'opel-astra',     1),
((SELECT id FROM brands WHERE slug='opel'), 'Corsa',     'opel-corsa',     1),
((SELECT id FROM brands WHERE slug='opel'), 'Insignia',  'opel-insignia',  1),
((SELECT id FROM brands WHERE slug='opel'), 'Mokka',     'opel-mokka',     1),
((SELECT id FROM brands WHERE slug='opel'), 'Crossland', 'opel-crossland', 1),
((SELECT id FROM brands WHERE slug='opel'), 'Grandland', 'opel-grandland', 1),
((SELECT id FROM brands WHERE slug='opel'), 'Zafira',    'opel-zafira',    1),
((SELECT id FROM brands WHERE slug='opel'), 'Vectra',    'opel-vectra',    1);

-- Ford
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='ford'), 'Fiesta',   'ford-fiesta',   1),
((SELECT id FROM brands WHERE slug='ford'), 'Focus',    'ford-focus',    1),
((SELECT id FROM brands WHERE slug='ford'), 'Mondeo',   'ford-mondeo',   1),
((SELECT id FROM brands WHERE slug='ford'), 'Kuga',     'ford-kuga',     1),
((SELECT id FROM brands WHERE slug='ford'), 'Puma',     'ford-puma',     1),
((SELECT id FROM brands WHERE slug='ford'), 'Edge',     'ford-edge',     1),
((SELECT id FROM brands WHERE slug='ford'), 'EcoSport', 'ford-ecosport', 1),
((SELECT id FROM brands WHERE slug='ford'), 'Transit',  'ford-transit',  1),
((SELECT id FROM brands WHERE slug='ford'), 'Ranger',   'ford-ranger',   1),
((SELECT id FROM brands WHERE slug='ford'), 'Mustang',  'ford-mustang',  1),
((SELECT id FROM brands WHERE slug='ford'), 'Galaxy',   'ford-galaxy',   1);

-- Renault
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='renault'), 'Clio',     'renault-clio',     1),
((SELECT id FROM brands WHERE slug='renault'), 'Megane',   'renault-megane',   1),
((SELECT id FROM brands WHERE slug='renault'), 'Laguna',   'renault-laguna',   1),
((SELECT id FROM brands WHERE slug='renault'), 'Kadjar',   'renault-kadjar',   1),
((SELECT id FROM brands WHERE slug='renault'), 'Captur',   'renault-captur',   1),
((SELECT id FROM brands WHERE slug='renault'), 'Koleos',   'renault-koleos',   1),
((SELECT id FROM brands WHERE slug='renault'), 'Scenic',   'renault-scenic',   1),
((SELECT id FROM brands WHERE slug='renault'), 'Talisman', 'renault-talisman', 1),
((SELECT id FROM brands WHERE slug='renault'), 'Zoe',      'renault-zoe',      1),
((SELECT id FROM brands WHERE slug='renault'), 'Arkana',   'renault-arkana',   1),
((SELECT id FROM brands WHERE slug='renault'), 'Master',   'renault-master',   1);

-- Peugeot
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='peugeot'), '106',     'peugeot-106',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '107',     'peugeot-107',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '108',     'peugeot-108',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '206',     'peugeot-206',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '207',     'peugeot-207',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '208',     'peugeot-208',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '306',     'peugeot-306',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '307',     'peugeot-307',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '308',     'peugeot-308',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '407',     'peugeot-407',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '408',     'peugeot-408',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '508',     'peugeot-508',     1),
((SELECT id FROM brands WHERE slug='peugeot'), '2008',    'peugeot-2008',    1),
((SELECT id FROM brands WHERE slug='peugeot'), '3008',    'peugeot-3008',    1),
((SELECT id FROM brands WHERE slug='peugeot'), '5008',    'peugeot-5008',    1),
((SELECT id FROM brands WHERE slug='peugeot'), 'Partner', 'peugeot-partner', 1),
((SELECT id FROM brands WHERE slug='peugeot'), 'Expert',  'peugeot-expert',  1);

-- Citroën
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='citroen'), 'C1',           'citroen-c1',           1),
((SELECT id FROM brands WHERE slug='citroen'), 'C2',           'citroen-c2',           1),
((SELECT id FROM brands WHERE slug='citroen'), 'C3',           'citroen-c3',           1),
((SELECT id FROM brands WHERE slug='citroen'), 'C3 Aircross',  'citroen-c3-aircross',  1),
((SELECT id FROM brands WHERE slug='citroen'), 'C4',           'citroen-c4',           1),
((SELECT id FROM brands WHERE slug='citroen'), 'C4 Picasso',   'citroen-c4-picasso',   1),
((SELECT id FROM brands WHERE slug='citroen'), 'C5',           'citroen-c5',           1),
((SELECT id FROM brands WHERE slug='citroen'), 'C5 Aircross',  'citroen-c5-aircross',  1),
((SELECT id FROM brands WHERE slug='citroen'), 'Berlingo',     'citroen-berlingo',     1),
((SELECT id FROM brands WHERE slug='citroen'), 'Jumper',       'citroen-jumper',       1),
((SELECT id FROM brands WHERE slug='citroen'), 'SpaceTourer',  'citroen-spacetourer',  1);

-- Fiat
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='fiat'), '500',      'fiat-500',      1),
((SELECT id FROM brands WHERE slug='fiat'), 'Punto',    'fiat-punto',    1),
((SELECT id FROM brands WHERE slug='fiat'), 'Bravo',    'fiat-bravo',    1),
((SELECT id FROM brands WHERE slug='fiat'), 'Tipo',     'fiat-tipo',     1),
((SELECT id FROM brands WHERE slug='fiat'), 'Doblo',    'fiat-doblo',    1),
((SELECT id FROM brands WHERE slug='fiat'), 'Ducato',   'fiat-ducato',   1),
((SELECT id FROM brands WHERE slug='fiat'), 'Panda',    'fiat-panda',    1),
((SELECT id FROM brands WHERE slug='fiat'), 'Freemont', 'fiat-freemont', 1),
((SELECT id FROM brands WHERE slug='fiat'), '500X',     'fiat-500x',     1),
((SELECT id FROM brands WHERE slug='fiat'), '500L',     'fiat-500l',     1);

-- Alfa Romeo
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='alfa-romeo'), '147',       'alfa-147',       1),
((SELECT id FROM brands WHERE slug='alfa-romeo'), '156',       'alfa-156',       1),
((SELECT id FROM brands WHERE slug='alfa-romeo'), '159',       'alfa-159',       1),
((SELECT id FROM brands WHERE slug='alfa-romeo'), 'Giulia',    'alfa-giulia',    1),
((SELECT id FROM brands WHERE slug='alfa-romeo'), 'Stelvio',   'alfa-stelvio',   1),
((SELECT id FROM brands WHERE slug='alfa-romeo'), 'Tonale',    'alfa-tonale',    1),
((SELECT id FROM brands WHERE slug='alfa-romeo'), 'Mito',      'alfa-mito',      1),
((SELECT id FROM brands WHERE slug='alfa-romeo'), 'Giulietta', 'alfa-giulietta', 1);

-- Seat
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='seat'), 'Ibiza',    'seat-ibiza',    1),
((SELECT id FROM brands WHERE slug='seat'), 'Leon',     'seat-leon',     1),
((SELECT id FROM brands WHERE slug='seat'), 'Ateca',    'seat-ateca',    1),
((SELECT id FROM brands WHERE slug='seat'), 'Tarraco',  'seat-tarraco',  1),
((SELECT id FROM brands WHERE slug='seat'), 'Arona',    'seat-arona',    1),
((SELECT id FROM brands WHERE slug='seat'), 'Alhambra', 'seat-alhambra', 1),
((SELECT id FROM brands WHERE slug='seat'), 'Toledo',   'seat-toledo',   1);

-- Skoda
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='skoda'), 'Fabia',   'skoda-fabia',   1),
((SELECT id FROM brands WHERE slug='skoda'), 'Octavia', 'skoda-octavia', 1),
((SELECT id FROM brands WHERE slug='skoda'), 'Superb',  'skoda-superb',  1),
((SELECT id FROM brands WHERE slug='skoda'), 'Kodiaq',  'skoda-kodiaq',  1),
((SELECT id FROM brands WHERE slug='skoda'), 'Karoq',   'skoda-karoq',   1),
((SELECT id FROM brands WHERE slug='skoda'), 'Kamiq',   'skoda-kamiq',   1),
((SELECT id FROM brands WHERE slug='skoda'), 'Rapid',   'skoda-rapid',   1),
((SELECT id FROM brands WHERE slug='skoda'), 'Scala',   'skoda-scala',   1);

-- Volvo
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='volvo'), 'S60',  'volvo-s60',  1),
((SELECT id FROM brands WHERE slug='volvo'), 'S90',  'volvo-s90',  1),
((SELECT id FROM brands WHERE slug='volvo'), 'V40',  'volvo-v40',  1),
((SELECT id FROM brands WHERE slug='volvo'), 'V60',  'volvo-v60',  1),
((SELECT id FROM brands WHERE slug='volvo'), 'V90',  'volvo-v90',  1),
((SELECT id FROM brands WHERE slug='volvo'), 'XC40', 'volvo-xc40', 1),
((SELECT id FROM brands WHERE slug='volvo'), 'XC60', 'volvo-xc60', 1),
((SELECT id FROM brands WHERE slug='volvo'), 'XC90', 'volvo-xc90', 1);

-- Porsche
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='porsche'), '911',      'porsche-911',      1),
((SELECT id FROM brands WHERE slug='porsche'), 'Cayenne',  'porsche-cayenne',  1),
((SELECT id FROM brands WHERE slug='porsche'), 'Macan',    'porsche-macan',    1),
((SELECT id FROM brands WHERE slug='porsche'), 'Panamera', 'porsche-panamera', 1),
((SELECT id FROM brands WHERE slug='porsche'), 'Taycan',   'porsche-taycan',   1),
((SELECT id FROM brands WHERE slug='porsche'), '718',      'porsche-718',      1);

-- Land Rover
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='land-rover'), 'Defender',          'lr-defender',        1),
((SELECT id FROM brands WHERE slug='land-rover'), 'Discovery',         'lr-discovery',       1),
((SELECT id FROM brands WHERE slug='land-rover'), 'Discovery Sport',   'lr-discovery-sport', 1),
((SELECT id FROM brands WHERE slug='land-rover'), 'Range Rover',       'lr-range-rover',     1),
((SELECT id FROM brands WHERE slug='land-rover'), 'Range Rover Sport', 'lr-rr-sport',        1),
((SELECT id FROM brands WHERE slug='land-rover'), 'Range Rover Evoque','lr-rr-evoque',       1),
((SELECT id FROM brands WHERE slug='land-rover'), 'Freelander',        'lr-freelander',      1);

-- Jaguar
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='jaguar'), 'XE',    'jaguar-xe',     1),
((SELECT id FROM brands WHERE slug='jaguar'), 'XF',    'jaguar-xf',     1),
((SELECT id FROM brands WHERE slug='jaguar'), 'XJ',    'jaguar-xj',     1),
((SELECT id FROM brands WHERE slug='jaguar'), 'F-Pace','jaguar-f-pace', 1),
((SELECT id FROM brands WHERE slug='jaguar'), 'E-Pace','jaguar-e-pace', 1),
((SELECT id FROM brands WHERE slug='jaguar'), 'F-Type','jaguar-f-type', 1);

-- MINI
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='mini'), 'Cooper',     'mini-cooper',     1),
((SELECT id FROM brands WHERE slug='mini'), 'Clubman',    'mini-clubman',    1),
((SELECT id FROM brands WHERE slug='mini'), 'Countryman', 'mini-countryman', 1),
((SELECT id FROM brands WHERE slug='mini'), 'Paceman',    'mini-paceman',    1),
((SELECT id FROM brands WHERE slug='mini'), 'Cabrio',     'mini-cabrio',     1);

-- Dacia
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='dacia'), 'Sandero', 'dacia-sandero', 1),
((SELECT id FROM brands WHERE slug='dacia'), 'Logan',   'dacia-logan',   1),
((SELECT id FROM brands WHERE slug='dacia'), 'Duster',  'dacia-duster',  1),
((SELECT id FROM brands WHERE slug='dacia'), 'Dokker',  'dacia-dokker',  1),
((SELECT id FROM brands WHERE slug='dacia'), 'Lodgy',   'dacia-lodgy',   1),
((SELECT id FROM brands WHERE slug='dacia'), 'Jogger',  'dacia-jogger',  1),
((SELECT id FROM brands WHERE slug='dacia'), 'Spring',  'dacia-spring',  1);

-- Kia
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='kia'), 'Picanto',  'kia-picanto',  1),
((SELECT id FROM brands WHERE slug='kia'), 'Rio',      'kia-rio',      1),
((SELECT id FROM brands WHERE slug='kia'), 'Ceed',     'kia-ceed',     1),
((SELECT id FROM brands WHERE slug='kia'), 'ProCeed',  'kia-proceed',  1),
((SELECT id FROM brands WHERE slug='kia'), 'Sportage', 'kia-sportage', 1),
((SELECT id FROM brands WHERE slug='kia'), 'Sorento',  'kia-sorento',  1),
((SELECT id FROM brands WHERE slug='kia'), 'Stinger',  'kia-stinger',  1),
((SELECT id FROM brands WHERE slug='kia'), 'Niro',     'kia-niro',     1),
((SELECT id FROM brands WHERE slug='kia'), 'EV6',      'kia-ev6',      1),
((SELECT id FROM brands WHERE slug='kia'), 'Stonic',   'kia-stonic',   1);

-- Hyundai
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='hyundai'), 'i10',      'hyundai-i10',      1),
((SELECT id FROM brands WHERE slug='hyundai'), 'i20',      'hyundai-i20',      1),
((SELECT id FROM brands WHERE slug='hyundai'), 'i30',      'hyundai-i30',      1),
((SELECT id FROM brands WHERE slug='hyundai'), 'i40',      'hyundai-i40',      1),
((SELECT id FROM brands WHERE slug='hyundai'), 'Tucson',   'hyundai-tucson',   1),
((SELECT id FROM brands WHERE slug='hyundai'), 'Santa Fe', 'hyundai-santa-fe', 1),
((SELECT id FROM brands WHERE slug='hyundai'), 'Kona',     'hyundai-kona',     1),
((SELECT id FROM brands WHERE slug='hyundai'), 'Ioniq',    'hyundai-ioniq',    1),
((SELECT id FROM brands WHERE slug='hyundai'), 'Ioniq 5',  'hyundai-ioniq5',   1),
((SELECT id FROM brands WHERE slug='hyundai'), 'Ioniq 6',  'hyundai-ioniq6',   1);

-- Toyota
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='toyota'), 'Yaris',       'toyota-yaris',       1),
((SELECT id FROM brands WHERE slug='toyota'), 'Corolla',     'toyota-corolla',     1),
((SELECT id FROM brands WHERE slug='toyota'), 'Avensis',     'toyota-avensis',     1),
((SELECT id FROM brands WHERE slug='toyota'), 'Camry',       'toyota-camry',       1),
((SELECT id FROM brands WHERE slug='toyota'), 'C-HR',        'toyota-chr',         1),
((SELECT id FROM brands WHERE slug='toyota'), 'RAV4',        'toyota-rav4',        1),
((SELECT id FROM brands WHERE slug='toyota'), 'Land Cruiser','toyota-land-cruiser',1),
((SELECT id FROM brands WHERE slug='toyota'), 'Prius',       'toyota-prius',       1),
((SELECT id FROM brands WHERE slug='toyota'), 'Hilux',       'toyota-hilux',       1),
((SELECT id FROM brands WHERE slug='toyota'), 'ProAce',      'toyota-proace',      1);

-- Honda
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='honda'), 'Jazz',   'honda-jazz',   1),
((SELECT id FROM brands WHERE slug='honda'), 'Civic',  'honda-civic',  1),
((SELECT id FROM brands WHERE slug='honda'), 'Accord', 'honda-accord', 1),
((SELECT id FROM brands WHERE slug='honda'), 'CR-V',   'honda-crv',    1),
((SELECT id FROM brands WHERE slug='honda'), 'HR-V',   'honda-hrv',    1),
((SELECT id FROM brands WHERE slug='honda'), 'e',      'honda-e',      1);

-- Nissan
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='nissan'), 'Micra',   'nissan-micra',   1),
((SELECT id FROM brands WHERE slug='nissan'), 'Juke',    'nissan-juke',    1),
((SELECT id FROM brands WHERE slug='nissan'), 'Qashqai', 'nissan-qashqai', 1),
((SELECT id FROM brands WHERE slug='nissan'), 'X-Trail', 'nissan-x-trail', 1),
((SELECT id FROM brands WHERE slug='nissan'), 'Navara',  'nissan-navara',  1),
((SELECT id FROM brands WHERE slug='nissan'), 'Leaf',    'nissan-leaf',    1),
((SELECT id FROM brands WHERE slug='nissan'), 'Ariya',   'nissan-ariya',   1),
((SELECT id FROM brands WHERE slug='nissan'), 'NV200',   'nissan-nv200',   1);

-- Mazda
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='mazda'), 'Mazda2', 'mazda2',     1),
((SELECT id FROM brands WHERE slug='mazda'), 'Mazda3', 'mazda3',     1),
((SELECT id FROM brands WHERE slug='mazda'), 'Mazda6', 'mazda6',     1),
((SELECT id FROM brands WHERE slug='mazda'), 'CX-3',   'mazda-cx3',  1),
((SELECT id FROM brands WHERE slug='mazda'), 'CX-5',   'mazda-cx5',  1),
((SELECT id FROM brands WHERE slug='mazda'), 'CX-30',  'mazda-cx30', 1),
((SELECT id FROM brands WHERE slug='mazda'), 'MX-5',   'mazda-mx5',  1);

-- Subaru
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='subaru'), 'Impreza',  'subaru-impreza',  1),
((SELECT id FROM brands WHERE slug='subaru'), 'Legacy',   'subaru-legacy',   1),
((SELECT id FROM brands WHERE slug='subaru'), 'Outback',  'subaru-outback',  1),
((SELECT id FROM brands WHERE slug='subaru'), 'Forester', 'subaru-forester', 1),
((SELECT id FROM brands WHERE slug='subaru'), 'XV',       'subaru-xv',       1),
((SELECT id FROM brands WHERE slug='subaru'), 'WRX STI',  'subaru-wrx-sti',  1),
((SELECT id FROM brands WHERE slug='subaru'), 'BRZ',      'subaru-brz',      1);

-- Mitsubishi
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='mitsubishi'), 'Colt',          'mitsubishi-colt',          1),
((SELECT id FROM brands WHERE slug='mitsubishi'), 'Lancer',        'mitsubishi-lancer',        1),
((SELECT id FROM brands WHERE slug='mitsubishi'), 'Outlander',     'mitsubishi-outlander',     1),
((SELECT id FROM brands WHERE slug='mitsubishi'), 'Eclipse Cross', 'mitsubishi-eclipse-cross', 1),
((SELECT id FROM brands WHERE slug='mitsubishi'), 'ASX',           'mitsubishi-asx',           1),
((SELECT id FROM brands WHERE slug='mitsubishi'), 'L200',          'mitsubishi-l200',          1),
((SELECT id FROM brands WHERE slug='mitsubishi'), 'Pajero',        'mitsubishi-pajero',        1);

-- Lancia
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='lancia'), 'Ypsilon', 'lancia-ypsilon', 1),
((SELECT id FROM brands WHERE slug='lancia'), 'Delta',   'lancia-delta',   1),
((SELECT id FROM brands WHERE slug='lancia'), 'Thesis',  'lancia-thesis',  1);

-- Saab
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='saab'), '9-3', 'saab-9-3', 1),
((SELECT id FROM brands WHERE slug='saab'), '9-5', 'saab-9-5', 1);

-- Chevrolet
INSERT IGNORE INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES
((SELECT id FROM brands WHERE slug='chevrolet'), 'Spark',   'chevrolet-spark',   1),
((SELECT id FROM brands WHERE slug='chevrolet'), 'Aveo',    'chevrolet-aveo',    1),
((SELECT id FROM brands WHERE slug='chevrolet'), 'Cruze',   'chevrolet-cruze',   1),
((SELECT id FROM brands WHERE slug='chevrolet'), 'Malibu',  'chevrolet-malibu',  1),
((SELECT id FROM brands WHERE slug='chevrolet'), 'Captiva', 'chevrolet-captiva', 1),
((SELECT id FROM brands WHERE slug='chevrolet'), 'Trax',    'chevrolet-trax',    1);

-- ----------------------------------------
-- GENERATIONS (model slug ile subquery)
-- ----------------------------------------

-- VW Golf
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='vw-golf'), 'Golf Mk4 (1997-2003)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-golf'), 'Golf Mk5 (2003-2008)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-golf'), 'Golf Mk6 (2008-2012)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-golf'), 'Golf Mk7 (2012-2019)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-golf'), 'Golf Mk8 (2019-...)',  1);

-- VW Passat
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='vw-passat'), 'Passat B5 (1996-2005)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-passat'), 'Passat B6 (2005-2010)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-passat'), 'Passat B7 (2010-2014)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-passat'), 'Passat B8 (2014-2023)', 1);

-- VW Polo
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='vw-polo'), 'Polo Mk4 (2001-2009)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-polo'), 'Polo Mk5 (2009-2017)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-polo'), 'Polo Mk6 (2017-...)',  1);

-- VW Tiguan
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='vw-tiguan'), 'Tiguan Mk1 (2007-2016)', 1),
((SELECT id FROM vehicle_models WHERE slug='vw-tiguan'), 'Tiguan Mk2 (2016-...)',  1);

-- BMW 3 Serisi
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi'), 'E46 (1997-2006)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi'), 'E90 (2005-2011)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi'), 'F30 (2011-2018)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi'), 'G20 (2018-...)',  1);

-- BMW 5 Serisi
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi'), 'E60 (2003-2010)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi'), 'F10 (2010-2016)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi'), 'G30 (2016-...)',  1);

-- BMW X5
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='bmw-x5'), 'E53 (1999-2006)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-x5'), 'E70 (2006-2013)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-x5'), 'F15 (2013-2018)', 1),
((SELECT id FROM vehicle_models WHERE slug='bmw-x5'), 'G05 (2018-...)',  1);

-- Mercedes C-Serisi
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='mb-c-serisi'), 'W203 (2000-2007)', 1),
((SELECT id FROM vehicle_models WHERE slug='mb-c-serisi'), 'W204 (2007-2014)', 1),
((SELECT id FROM vehicle_models WHERE slug='mb-c-serisi'), 'W205 (2014-2021)', 1),
((SELECT id FROM vehicle_models WHERE slug='mb-c-serisi'), 'W206 (2021-...)',  1);

-- Mercedes E-Serisi
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='mb-e-serisi'), 'W210 (1995-2002)', 1),
((SELECT id FROM vehicle_models WHERE slug='mb-e-serisi'), 'W211 (2002-2009)', 1),
((SELECT id FROM vehicle_models WHERE slug='mb-e-serisi'), 'W212 (2009-2016)', 1),
((SELECT id FROM vehicle_models WHERE slug='mb-e-serisi'), 'W213 (2016-...)',  1);

-- Audi A4
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='audi-a4'), 'B6 (2000-2004)', 1),
((SELECT id FROM vehicle_models WHERE slug='audi-a4'), 'B7 (2004-2008)', 1),
((SELECT id FROM vehicle_models WHERE slug='audi-a4'), 'B8 (2007-2015)', 1),
((SELECT id FROM vehicle_models WHERE slug='audi-a4'), 'B9 (2015-...)',  1);

-- Audi A6
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='audi-a6'), 'C5 (1997-2004)', 1),
((SELECT id FROM vehicle_models WHERE slug='audi-a6'), 'C6 (2004-2011)', 1),
((SELECT id FROM vehicle_models WHERE slug='audi-a6'), 'C7 (2011-2018)', 1),
((SELECT id FROM vehicle_models WHERE slug='audi-a6'), 'C8 (2018-...)',  1);

-- Opel Astra
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='opel-astra'), 'Astra G (1998-2004)', 1),
((SELECT id FROM vehicle_models WHERE slug='opel-astra'), 'Astra H (2004-2010)', 1),
((SELECT id FROM vehicle_models WHERE slug='opel-astra'), 'Astra J (2009-2015)', 1),
((SELECT id FROM vehicle_models WHERE slug='opel-astra'), 'Astra K (2015-2022)', 1),
((SELECT id FROM vehicle_models WHERE slug='opel-astra'), 'Astra L (2022-...)',  1);

-- Ford Focus
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='ford-focus'), 'Mk1 (1998-2004)', 1),
((SELECT id FROM vehicle_models WHERE slug='ford-focus'), 'Mk2 (2004-2011)', 1),
((SELECT id FROM vehicle_models WHERE slug='ford-focus'), 'Mk3 (2011-2018)', 1),
((SELECT id FROM vehicle_models WHERE slug='ford-focus'), 'Mk4 (2018-...)',  1);

-- Renault Megane
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='renault-megane'), 'Mk1 (1995-2002)', 1),
((SELECT id FROM vehicle_models WHERE slug='renault-megane'), 'Mk2 (2002-2008)', 1),
((SELECT id FROM vehicle_models WHERE slug='renault-megane'), 'Mk3 (2008-2016)', 1),
((SELECT id FROM vehicle_models WHERE slug='renault-megane'), 'Mk4 (2016-...)',  1);

-- Skoda Octavia
INSERT IGNORE INTO `generations` (`model_id`, `name`, `is_active`) VALUES
((SELECT id FROM vehicle_models WHERE slug='skoda-octavia'), 'Mk1 (1996-2010)', 1),
((SELECT id FROM vehicle_models WHERE slug='skoda-octavia'), 'Mk2 (2004-2013)', 1),
((SELECT id FROM vehicle_models WHERE slug='skoda-octavia'), 'Mk3 (2012-2020)', 1),
((SELECT id FROM vehicle_models WHERE slug='skoda-octavia'), 'Mk4 (2020-...)',  1);

SET FOREIGN_KEY_CHECKS = 1;
