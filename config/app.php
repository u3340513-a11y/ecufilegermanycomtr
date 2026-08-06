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
        'allowed_types' => ['rar'],
        'allowed_mimes' => [
            'application/x-rar-compressed',
            'application/vnd.rar',
            'application/octet-stream',
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
