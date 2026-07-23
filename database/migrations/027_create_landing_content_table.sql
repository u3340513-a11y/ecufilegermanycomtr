CREATE TABLE IF NOT EXISTS `landing_content` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `section` VARCHAR(100) NOT NULL,
    `key_name` VARCHAR(100) NOT NULL,
    `label` VARCHAR(200) NOT NULL,
    `value` TEXT,
    `type` ENUM('text','textarea','image') NOT NULL DEFAULT 'text',
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_section_key` (`section`, `key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `landing_content` (`section`, `key_name`, `label`, `value`, `type`, `sort_order`) VALUES
('hero', 'badge',        'Badge Text',         'Professional ECU Tuning File Service', 'text', 1),
('hero', 'title',        'Main Title',         'Precision Tuning.', 'text', 2),
('hero', 'title_accent', 'Accent Title',       'Maximum Performance.', 'text', 3),
('hero', 'subtitle',     'Subtitle',           'Upload your ECU file, select your tune level, and get a professionally optimized file back — fast, secure, and engineered for results.', 'textarea', 4),
('hero', 'cta_primary',  'Primary Button',     "Start Now — It's Free", 'text', 5),
('hero', 'cta_secondary','Secondary Button',   'How It Works', 'text', 6),
('hero', 'stat1_val',    'Stat 1 Value',       '5,000+', 'text', 7),
('hero', 'stat1_lbl',    'Stat 1 Label',       'Files Processed', 'text', 8),
('hero', 'stat2_val',    'Stat 2 Value',       '500+', 'text', 9),
('hero', 'stat2_lbl',    'Stat 2 Label',       'Vehicle Models', 'text', 10),
('hero', 'stat3_val',    'Stat 3 Value',       '24h', 'text', 11),
('hero', 'stat3_lbl',    'Stat 3 Label',       'Avg. Turnaround', 'text', 12),

('notice', 'text',       'Service Notice Text', 'Our file service is currently down. Opening hours: Monday to Saturday 08:00(AM) – 07:00(PM) (UTC+3). Only support will be given on Sunday.', 'textarea', 1),

('how_it_works', 'eyebrow',   'Eyebrow',          'Simple Process', 'text', 1),
('how_it_works', 'title',     'Section Title',    'How It Works', 'text', 2),
('how_it_works', 'subtitle',  'Section Subtitle', 'From file upload to optimized output in three steps.', 'text', 3),
('how_it_works', 'step1_title','Step 1 Title',    'Upload Your File', 'text', 4),
('how_it_works', 'step1_desc', 'Step 1 Desc',     'Create a request, select your vehicle details, and upload your original ECU file securely through our platform.', 'textarea', 5),
('how_it_works', 'step2_title','Step 2 Title',    'Choose Your Tune', 'text', 6),
('how_it_works', 'step2_desc', 'Step 2 Desc',     'Select from Stage 1, Stage 2, Stage 3, or custom options. Add specific service requests and notes for our engineers.', 'textarea', 7),
('how_it_works', 'step3_title','Step 3 Title',    'Download & Drive', 'text', 8),
('how_it_works', 'step3_desc', 'Step 3 Desc',     'Receive your professionally tuned file, ready to flash. Track your request status in real-time on your dashboard.', 'textarea', 9),

('showcase', 'img1_label', 'Image 1 Label', 'Stage 3 Performance', 'text', 1),
('showcase', 'img1_src',   'Image 1',       'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800&q=85&auto=format&fit=crop', 'image', 2),
('showcase', 'img2_label', 'Image 2 Label', 'Stage 2 Tune', 'text', 3),
('showcase', 'img2_src',   'Image 2',       'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=85&auto=format&fit=crop', 'image', 4),
('showcase', 'img3_label', 'Image 3 Label', 'DPF / EGR Solutions', 'text', 5),
('showcase', 'img3_src',   'Image 3',       'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&q=85&auto=format&fit=crop', 'image', 6),
('showcase', 'img4_label', 'Image 4 Label', 'Stage 1 Entry Tune', 'text', 7),
('showcase', 'img4_src',   'Image 4',       'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=1200&q=85&auto=format&fit=crop', 'image', 8),

('about', 'eyebrow',  'Eyebrow',       'About Us', 'text', 1),
('about', 'heading',  'Heading',       'Who Is ECU File Germany?', 'text', 2),
('about', 'desc1',    'Paragraph 1',   'ECU File Germany is a Europe-based professional ECU software service platform. With branches across Germany, Belgium, Sweden and Turkey, we serve vehicle owners, garages, and tuning workshops — both locally and online.', 'textarea', 3),
('about', 'desc2',    'Paragraph 2',   'Our experienced team of engineers covers everything from Stage 1, 2 and 3 performance tunes to DPF/EGR solutions, pop & bang and launch control configurations. Every file is custom-built and quality-checked for your specific vehicle.', 'textarea', 4),
('about', 'stat1_val','Stat 1 Value',  '15', 'text', 5),
('about', 'stat1_lbl','Stat 1 Label',  'Branches', 'text', 6),
('about', 'stat2_val','Stat 2 Value',  '5+', 'text', 7),
('about', 'stat2_lbl','Stat 2 Label',  'Years Exp.', 'text', 8),
('about', 'stat3_val','Stat 3 Value',  '4', 'text', 9),
('about', 'stat3_lbl','Stat 3 Label',  'Countries', 'text', 10),
('about', 'stat4_val','Stat 4 Value',  '5K+', 'text', 11),
('about', 'stat4_lbl','Stat 4 Label',  'Files Done', 'text', 12),

('cta', 'title',    'CTA Title',    'Ready to Tune?', 'text', 1),
('cta', 'subtitle', 'CTA Subtitle', 'Create your free account and submit your first request today.', 'text', 2),
('cta', 'btn_text', 'Button Text',  'Create Free Account', 'text', 3),

('footer', 'tagline', 'Footer Tagline', 'Professional ECU file service for performance enthusiasts and tuning shops.', 'textarea', 1)

ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
