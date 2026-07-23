-- ============================================================
-- Motorlar Seed Verisi (mevcut generasyonlar için)
-- model slug → generation name üzerinden subquery
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------
-- VW GOLF
-- ----------------------------------------
-- Golf Mk4
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.4 16V',    '1390cc', 'Benzin', 75,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.6 SR',     '1598cc', 'Benzin', 101, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.8 20V',    '1781cc', 'Benzin', 125, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.8T',       '1781cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '2.0 8V',     '1984cc', 'Benzin', 115, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '2.3 V5',     '2324cc', 'Benzin', 170, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '2.8 V6 4Motion','2792cc','Benzin',204, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.9 SDI',    '1896cc', 'Dizel',  68,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.9 TDI 90', '1896cc', 'Dizel',  90,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.9 TDI 110','1896cc', 'Dizel',  110, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk4%'), '1.9 TDI 130','1896cc', 'Dizel',  130, 1);

-- Golf Mk5
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '1.4 FSI',    '1390cc', 'Benzin', 90,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '1.4 TSI 122','1390cc', 'Benzin', 122, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '1.4 TSI 140','1390cc', 'Benzin', 140, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '1.6',        '1598cc', 'Benzin', 102, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '2.0 FSI',    '1984cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '2.0 TSI',    '1984cc', 'Benzin', 200, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '3.2 V6 R32', '3189cc', 'Benzin', 250, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '1.9 TDI',    '1896cc', 'Dizel',  105, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk5%'), '2.0 TDI 140','1968cc', 'Dizel',  140, 1);

-- Golf Mk6
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk6%'), '1.2 TSI 86', '1197cc', 'Benzin', 86,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk6%'), '1.4 TSI 122','1390cc', 'Benzin', 122, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk6%'), '1.4 TSI 160','1390cc', 'Benzin', 160, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk6%'), '2.0 TSI GTI','1984cc', 'Benzin', 210, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk6%'), '2.0 TDI 110','1968cc', 'Dizel',  110, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk6%'), '2.0 TDI 140','1968cc', 'Dizel',  140, 1);

-- Golf Mk7
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '1.0 TSI 85', '999cc',  'Benzin', 85,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '1.0 TSI 110','999cc',  'Benzin', 110, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '1.2 TSI 105','1197cc', 'Benzin', 105, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '1.4 TSI 125','1395cc', 'Benzin', 125, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '1.5 TSI 130','1498cc', 'Benzin', 130, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '1.5 TSI 150','1498cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '2.0 TSI GTI','1984cc', 'Benzin', 220, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '2.0 TSI R',  '1984cc', 'Benzin', 310, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '1.6 TDI 105','1598cc', 'Dizel',  105, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '2.0 TDI 150','1968cc', 'Dizel',  150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk7%'), '2.0 TDI 184','1968cc', 'Dizel',  184, 1);

-- Golf Mk8
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk8%'), '1.0 eTSI 110','999cc', 'Hibrit', 110, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk8%'), '1.5 eTSI 150','1498cc','Hibrit', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk8%'), '2.0 TSI GTI','1984cc', 'Benzin', 245, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk8%'), '2.0 TSI R',  '1984cc', 'Benzin', 320, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk8%'), '2.0 TDI 115','1968cc', 'Dizel',  115, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-golf') AND name LIKE '%Mk8%'), '2.0 TDI 150','1968cc', 'Dizel',  150, 1);

-- ----------------------------------------
-- VW PASSAT
-- ----------------------------------------
-- Passat B5
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B5%' AND name LIKE '%1996%'), '1.6',        '1595cc', 'Benzin', 102, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B5%' AND name LIKE '%1996%'), '1.8T',       '1781cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B5%' AND name LIKE '%1996%'), '2.0',        '1984cc', 'Benzin', 115, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B5%' AND name LIKE '%1996%'), '2.8 V6',     '2771cc', 'Benzin', 193, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B5%' AND name LIKE '%1996%'), '1.9 TDI 90', '1896cc', 'Dizel',  90,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B5%' AND name LIKE '%1996%'), '1.9 TDI 110','1896cc', 'Dizel',  110, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B5%' AND name LIKE '%1996%'), '1.9 TDI 130','1896cc', 'Dizel',  130, 1);

-- Passat B6
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '1.4 TSI',    '1390cc', 'Benzin', 122, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '1.8 TSI',    '1798cc', 'Benzin', 160, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '2.0 FSI',    '1984cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '2.0 TSI',    '1984cc', 'Benzin', 200, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '3.2 V6',     '3189cc', 'Benzin', 250, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '1.9 TDI',    '1896cc', 'Dizel',  105, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '2.0 TDI 140','1968cc', 'Dizel',  140, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B6%'), '2.0 TDI 170','1968cc', 'Dizel',  170, 1);

-- Passat B7
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B7%'), '1.4 TSI 122','1390cc', 'Benzin', 122, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B7%'), '1.8 TSI 160','1798cc', 'Benzin', 160, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B7%'), '2.0 TSI 210','1984cc', 'Benzin', 210, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B7%'), '2.0 TDI 140','1968cc', 'Dizel',  140, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B7%'), '2.0 TDI 177','1968cc', 'Dizel',  177, 1);

-- Passat B8
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B8%'), '1.4 TSI 150','1395cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B8%'), '1.8 TSI 180','1798cc', 'Benzin', 180, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B8%'), '2.0 TSI 220','1984cc', 'Benzin', 220, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B8%'), '2.0 TDI 150','1968cc', 'Dizel',  150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B8%'), '2.0 TDI 190','1968cc', 'Dizel',  190, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='vw-passat') AND name LIKE '%B8%'), '2.0 BiTDI 240','1968cc','Dizel', 240, 1);

-- ----------------------------------------
-- BMW 3 SERİSİ
-- ----------------------------------------
-- E46
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '316i',    '1796cc', 'Benzin', 116, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '318i',    '1895cc', 'Benzin', 118, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '320i',    '1991cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '323i',    '2494cc', 'Benzin', 170, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '325i',    '2494cc', 'Benzin', 192, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '328i',    '2793cc', 'Benzin', 193, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '330i',    '2979cc', 'Benzin', 231, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), 'M3 3.2',  '3246cc', 'Benzin', 343, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '320d',    '1951cc', 'Dizel',  150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E46%'), '330d',    '2926cc', 'Dizel',  204, 1);

-- E90
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '316i',    '1596cc', 'Benzin', 122, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '318i',    '1995cc', 'Benzin', 129, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '320i',    '1995cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '325i',    '2996cc', 'Benzin', 218, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '330i',    '2996cc', 'Benzin', 258, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '335i',    '2979cc', 'Benzin', 306, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), 'M3 4.0',  '3999cc', 'Benzin', 420, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '318d',    '1995cc', 'Dizel',  143, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '320d',    '1995cc', 'Dizel',  163, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '325d',    '2993cc', 'Dizel',  197, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '330d',    '2993cc', 'Dizel',  231, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%E90%'), '335d',    '2993cc', 'Dizel',  286, 1);

-- F30
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '316i',    '1499cc', 'Benzin', 136, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '318i',    '1499cc', 'Benzin', 136, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '320i',    '1997cc', 'Benzin', 184, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '328i',    '1997cc', 'Benzin', 245, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '335i',    '2979cc', 'Benzin', 306, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), 'M3 3.0',  '2979cc', 'Benzin', 431, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '316d',    '1995cc', 'Dizel',  116, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '318d',    '1995cc', 'Dizel',  143, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '320d',    '1995cc', 'Dizel',  184, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '325d',    '1995cc', 'Dizel',  218, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-3-serisi') AND name LIKE '%F30%'), '330d',    '2993cc', 'Dizel',  258, 1);

-- ----------------------------------------
-- BMW 5 SERİSİ
-- ----------------------------------------
-- E60
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '520i',    '2171cc', 'Benzin', 170, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '523i',    '2494cc', 'Benzin', 177, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '525i',    '2996cc', 'Benzin', 218, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '530i',    '2996cc', 'Benzin', 258, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '545i',    '4398cc', 'Benzin', 333, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), 'M5 5.0',  '4999cc', 'Benzin', 507, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '520d',    '1995cc', 'Dizel',  163, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '525d',    '2497cc', 'Dizel',  177, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%E60%'), '530d',    '2993cc', 'Dizel',  218, 1);

-- F10
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), '520i',    '1997cc', 'Benzin', 184, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), '528i',    '1997cc', 'Benzin', 245, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), '535i',    '2979cc', 'Benzin', 306, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), 'M5 4.4',  '4395cc', 'Benzin', 560, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), '518d',    '1995cc', 'Dizel',  143, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), '520d',    '1995cc', 'Dizel',  184, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), '525d',    '1995cc', 'Dizel',  218, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='bmw-5-serisi') AND name LIKE '%F10%'), '530d',    '2993cc', 'Dizel',  258, 1);

-- ----------------------------------------
-- MERCEDES C SERİSİ
-- ----------------------------------------
-- W203
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C180 1.8',  '1796cc', 'Benzin', 129, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C200 1.8K', '1796cc', 'Benzin', 163, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C220 2.2',  '2196cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C240 2.6',  '2597cc', 'Benzin', 177, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C320 3.2',  '3199cc', 'Benzin', 218, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C55 AMG',   '5439cc', 'Benzin', 367, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C200 CDI',  '2148cc', 'Dizel',  116, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C220 CDI',  '2148cc', 'Dizel',  143, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W203%'), 'C270 CDI',  '2685cc', 'Dizel',  170, 1);

-- W204
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C180 1.6T', '1597cc', 'Benzin', 156, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C200 1.8T', '1796cc', 'Benzin', 184, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C250 1.8T', '1796cc', 'Benzin', 204, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C300 3.0',  '2996cc', 'Benzin', 231, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C63 AMG',   '6208cc', 'Benzin', 457, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C200 CDI',  '2143cc', 'Dizel',  136, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C220 CDI',  '2143cc', 'Dizel',  170, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W204%'), 'C250 CDI',  '2143cc', 'Dizel',  204, 1);

-- W205
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C180 1.6T', '1595cc', 'Benzin', 156, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C200 2.0T', '1991cc', 'Benzin', 184, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C300 2.0T', '1991cc', 'Benzin', 258, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C43 AMG',   '2996cc', 'Benzin', 390, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C63 AMG',   '3982cc', 'Benzin', 476, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C200d 2.0', '1950cc', 'Dizel',  160, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C220d 2.0', '1950cc', 'Dizel',  194, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-c-serisi') AND name LIKE '%W205%'), 'C250d 2.1', '2143cc', 'Dizel',  204, 1);

-- ----------------------------------------
-- MERCEDES E SERİSİ
-- ----------------------------------------
-- W211
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E200 1.8K', '1796cc', 'Benzin', 163, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E240 2.6',  '2597cc', 'Benzin', 177, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E280 3.0',  '2996cc', 'Benzin', 231, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E350 3.5',  '3498cc', 'Benzin', 272, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E55 AMG',   '5439cc', 'Benzin', 476, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E200 CDI',  '2148cc', 'Dizel',  136, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E220 CDI',  '2148cc', 'Dizel',  150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E270 CDI',  '2685cc', 'Dizel',  177, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W211%'), 'E320 CDI',  '2987cc', 'Dizel',  204, 1);

-- W212
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E200 2.0T', '1796cc', 'Benzin', 184, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E250 2.0T', '1796cc', 'Benzin', 204, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E350 3.5',  '3498cc', 'Benzin', 306, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E63 AMG',   '5461cc', 'Benzin', 525, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E200 CDI',  '2143cc', 'Dizel',  136, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E220 CDI',  '2143cc', 'Dizel',  170, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E250 CDI',  '2143cc', 'Dizel',  204, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='mb-e-serisi') AND name LIKE '%W212%'), 'E350 CDI',  '2987cc', 'Dizel',  265, 1);

-- ----------------------------------------
-- AUDİ A4
-- ----------------------------------------
-- B6
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B6%'), '1.6',       '1595cc', 'Benzin', 102, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B6%'), '1.8T',      '1781cc', 'Benzin', 163, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B6%'), '2.0',       '1984cc', 'Benzin', 131, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B6%'), '3.0 V6',    '2976cc', 'Benzin', 220, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B6%'), '1.9 TDI 100','1896cc','Dizel',  100, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B6%'), '1.9 TDI 130','1896cc','Dizel',  130, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B6%'), '2.5 TDI',   '2496cc', 'Dizel',  163, 1);

-- B8
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B8%'), '1.8 TFSI',  '1798cc', 'Benzin', 160, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B8%'), '2.0 TFSI',  '1984cc', 'Benzin', 211, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B8%'), '3.0 TFSI',  '2995cc', 'Benzin', 272, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B8%'), 'S4 3.0T',   '2995cc', 'Benzin', 333, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B8%'), '2.0 TDI 143','1968cc','Dizel',  143, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B8%'), '2.0 TDI 177','1968cc','Dizel',  177, 1);

-- B9
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B9%'), '1.4 TFSI',  '1395cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B9%'), '2.0 TFSI 190','1984cc','Benzin',190, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B9%'), '2.0 TFSI 252','1984cc','Benzin',252, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B9%'), 'S4 3.0T',   '2995cc', 'Benzin', 354, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B9%'), '2.0 TDI 150','1968cc','Dizel',  150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a4') AND name LIKE '%B9%'), '2.0 TDI 190','1968cc','Dizel',  190, 1);

-- ----------------------------------------
-- AUDİ A6
-- ----------------------------------------
-- C6
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C6%'), '2.4',       '2393cc', 'Benzin', 177, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C6%'), '2.8 FSI',   '2773cc', 'Benzin', 187, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C6%'), '3.0 TFSI',  '2995cc', 'Benzin', 290, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C6%'), 'S6 5.2 V10','5204cc', 'Benzin', 435, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C6%'), '2.0 TDI',   '1968cc', 'Dizel',  140, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C6%'), '2.7 TDI',   '2698cc', 'Dizel',  180, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C6%'), '3.0 TDI',   '2967cc', 'Dizel',  225, 1);

-- C7
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C7%'), '1.8 TFSI',  '1798cc', 'Benzin', 170, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C7%'), '2.0 TFSI',  '1984cc', 'Benzin', 252, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C7%'), '3.0 TFSI',  '2995cc', 'Benzin', 310, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C7%'), 'S6 4.0T',   '3993cc', 'Benzin', 420, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C7%'), '2.0 TDI 177','1968cc','Dizel',  177, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='audi-a6') AND name LIKE '%C7%'), '3.0 TDI 245','2967cc','Dizel',  245, 1);

-- ----------------------------------------
-- OPEL ASTRA
-- ----------------------------------------
-- Astra H
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), '1.4 16V',   '1364cc', 'Benzin', 90,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), '1.6 16V',   '1598cc', 'Benzin', 115, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), '1.8 16V',   '1796cc', 'Benzin', 125, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), '2.0 Turbo', '1998cc', 'Benzin', 200, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), 'OPC 2.0T',  '1998cc', 'Benzin', 240, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), '1.7 CDTI',  '1686cc', 'Dizel',  101, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), '1.9 CDTI 120','1910cc','Dizel', 120, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%H%'), '1.9 CDTI 150','1910cc','Dizel', 150, 1);

-- Astra J
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%J%'), '1.4 Turbo 100','1364cc','Benzin',100, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%J%'), '1.4 Turbo 140','1364cc','Benzin',140, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%J%'), '1.6 Turbo',  '1598cc', 'Benzin', 180, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%J%'), '2.0 OPC',    '1998cc', 'Benzin', 280, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%J%'), '1.6 CDTI',   '1598cc', 'Dizel',  136, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='opel-astra') AND name LIKE '%J%'), '2.0 CDTI 165','1956cc','Dizel',  165, 1);

-- ----------------------------------------
-- FORD FOCUS
-- ----------------------------------------
-- Mk2
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '1.4',       '1388cc', 'Benzin', 80,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '1.6',       '1596cc', 'Benzin', 100, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '1.8',       '1798cc', 'Benzin', 125, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '2.0',       '1999cc', 'Benzin', 145, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), 'ST 2.5T',   '2521cc', 'Benzin', 225, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '1.6 TDCi 90','1560cc','Dizel',  90,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '1.6 TDCi 110','1560cc','Dizel', 110, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '1.8 TDCi',  '1753cc', 'Dizel',  116, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk2%'), '2.0 TDCi',  '1998cc', 'Dizel',  136, 1);

-- Mk3
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), '1.0 EcoBoost 100','998cc','Benzin',100, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), '1.0 EcoBoost 125','998cc','Benzin',125, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), '1.6 EcoBoost',   '1596cc','Benzin',150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), '2.0 EcoBoost ST','1999cc','Benzin',250, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), 'RS 2.3 EcoBoost','2261cc','Benzin',350, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), '1.5 TDCi 95', '1499cc', 'Dizel',  95,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), '1.5 TDCi 120','1499cc', 'Dizel',  120, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='ford-focus') AND name LIKE '%Mk3%'), '2.0 TDCi 150','1997cc', 'Dizel',  150, 1);

-- ----------------------------------------
-- RENAULT MEGANE
-- ----------------------------------------
-- Mk2
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), '1.4 16V',  '1390cc', 'Benzin', 98,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), '1.6 16V',  '1598cc', 'Benzin', 113, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), '2.0 Turbo','1998cc', 'Benzin', 165, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), 'RS 2.0',   '1998cc', 'Benzin', 225, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), '1.5 dCi 80','1461cc','Dizel',  80,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), '1.5 dCi 100','1461cc','Dizel', 100, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), '1.9 dCi 120','1870cc','Dizel', 120, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk2%'), '2.0 dCi 150','1995cc','Dizel', 150, 1);

-- Mk3
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk3%'), '1.2 TCe 115','1197cc','Benzin',115, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk3%'), '1.4 TCe 130','1390cc','Benzin',130, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk3%'), '2.0 Turbo', '1998cc', 'Benzin', 180, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk3%'), 'RS 2.0',    '1998cc', 'Benzin', 265, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk3%'), '1.5 dCi 90','1461cc', 'Dizel',  90,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk3%'), '1.5 dCi 110','1461cc','Dizel',  110, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='renault-megane') AND name LIKE '%Mk3%'), '2.0 dCi 160','1995cc','Dizel',  160, 1);

-- ----------------------------------------
-- SKODA OCTAVIA
-- ----------------------------------------
-- Mk2
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk2%'), '1.4',       '1390cc', 'Benzin', 75,  1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk2%'), '1.6',       '1598cc', 'Benzin', 102, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk2%'), '1.8 TSI',   '1798cc', 'Benzin', 160, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk2%'), '2.0 TSI RS','1984cc', 'Benzin', 200, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk2%'), '1.9 TDI',   '1896cc', 'Dizel',  105, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk2%'), '2.0 TDI',   '1968cc', 'Dizel',  140, 1);

-- Mk3
INSERT IGNORE INTO `engines` (`generation_id`, `name`, `displacement`, `fuel_type`, `horsepower`, `is_active`) VALUES
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk3%'), '1.0 TSI',   '999cc',  'Benzin', 115, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk3%'), '1.4 TSI',   '1395cc', 'Benzin', 150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk3%'), '1.8 TSI',   '1798cc', 'Benzin', 180, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk3%'), '2.0 TSI RS','1984cc', 'Benzin', 220, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk3%'), '1.6 TDI',   '1598cc', 'Dizel',  105, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk3%'), '2.0 TDI 150','1968cc','Dizel',  150, 1),
((SELECT id FROM generations WHERE model_id=(SELECT id FROM vehicle_models WHERE slug='skoda-octavia') AND name LIKE '%Mk3%'), '2.0 TDI 184','1968cc','Dizel',  184, 1);

SET FOREIGN_KEY_CHECKS = 1;
