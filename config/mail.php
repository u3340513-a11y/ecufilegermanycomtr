<?php

return [
    'host'       => env('MAIL_HOST', 'localhost'),
    'port'       => (int) env('MAIL_PORT', 465),
    'username'   => env('MAIL_USER', ''),
    'password'   => env('MAIL_PASS', ''),
    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
    'from_email' => env('MAIL_FROM_EMAIL', ''),
    'from_name'  => env('MAIL_FROM_NAME', 'ECU Dosya Servis'),
];
