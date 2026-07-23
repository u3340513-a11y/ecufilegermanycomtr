UPDATE `users` 
SET 
    `name`  = 'Admin',
    `email` = 'info@ecufilegermany.com.tr',
    `password` = '$2y$12$E986mI0csYZ2LUeWiPHPxuu035sT2TY7TfyE7L.a1kl5Fh070c1UK',
    `role`  = 'admin',
    `email_verified` = 1,
    `is_active` = 1
WHERE `role` = 'admin'
LIMIT 1;
