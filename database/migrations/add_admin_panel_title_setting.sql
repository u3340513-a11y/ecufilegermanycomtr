-- Adds admin_panel_title to the settings table so the admin can change
-- the sidebar title from Ayarlar > Genel Ayarlar without code changes.
INSERT IGNORE INTO settings (key_name, value, group_name)
VALUES ('admin_panel_title', 'ECU Admin', 'general');
