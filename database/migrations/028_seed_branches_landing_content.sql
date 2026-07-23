INSERT INTO `landing_content` (`section`, `key_name`, `label`, `value`, `type`, `sort_order`) VALUES
('branches', 'eyebrow',        'Eyebrow Text',         'Our Locations',                                           'text',     1),
('branches', 'title',          'Section Title',        'Our Branches',                                            'text',     2),
('branches', 'subtitle',       'Section Subtitle',     '15 locations across Europe and Turkey — always close to you.', 'text', 3),
('branches', 'germany_label',  'Germany Group Label',  'Germany',                                                 'text',     4),
('branches', 'germany_cities', 'Germany Cities',       'Bielefeld, Duisburg, Stuttgart, München, Köln',           'textarea', 5),
('branches', 'belgium_label',  'Belgium Group Label',  'Belgium',                                                 'text',     6),
('branches', 'belgium_cities', 'Belgium Cities',       'Evergem, Aarschot',                                       'textarea', 7),
('branches', 'sweden_label',   'Sweden Group Label',   'Sweden',                                                  'text',     8),
('branches', 'sweden_cities',  'Sweden Cities',        'Sweden',                                                  'textarea', 9),
('branches', 'turkey_label',   'Turkey Group Label',   'Turkey',                                                  'text',     10),
('branches', 'turkey_cities',  'Turkey Cities',        'Konya, Gaziantep, Adana, Nigde, Sirnak, Samsun, Batman',  'textarea', 11)
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
