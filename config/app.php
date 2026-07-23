<?php

return [
    'name'    => 'ECU Dosya Servis',
    'url'     => 'https://ecufilegermany.com.tr',
    'debug'   => true,
    'timezone' => 'Europe/Istanbul',
    'charset' => 'UTF-8',

    'session' => [
        'name'     => 'ECU_SESSION',
        'lifetime' => 7200,
        'domain'   => '.ecufilegermany.com.tr',
        'secure'   => true,
    ],

    'upload' => [
        'max_size'      => 20971520,
        'allowed_types' => ['bin', 'ori', 'mod', 'zip', 'rar', '7z', 'pdf', 'jpg', 'jpeg', 'png'],
        'allowed_mimes' => [
            'application/octet-stream',
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
            'application/pdf',
            'image/jpeg',
            'image/png',
        ],
    ],

    'rate_limit' => [
        'login' => [
            'max_attempts' => 5,
            'window'       => 900,
        ],
        'api' => [
            'max_attempts' => 60,
            'window'       => 60,
        ],
    ],
];
