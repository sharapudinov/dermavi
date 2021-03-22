<?php

use Bitrix\Main\DB\MysqliConnection;

require_once 'bootstrap.php';

return [
    'analytics_counter'  => [
        'value' => [
            'enabled' => false,
        ],
    ],
    'composer'           => [
        'value' => ['config_path' => $_SERVER['DOCUMENT_ROOT'] . '/../composer.json']
    ],
    'exception_handling' => [
        'value'    => [
            'debug'                      => env('APP_DEBUG', false),
            'handled_errors_types'       => 4437,
            'exception_errors_types'     => 4437,
            'ignore_silence'             => false,
            'assertion_throws_exception' => true,
            'assertion_error_type'       => 256,
            'log'                        => null,
        ],
        'readonly' => false,
    ],
    'connections'        => [
        'value'    => [
            'default' => [
                'className' => MysqliConnection::class,
                'host'      => env('DB_HOST', 'localhost'),
                'database'  => env('DB_DATABASE', ''),
                'login'     => env('DB_LOGIN', ''),
                'password'  => env('DB_PASSWORD', ''),
                'options'   => 2,
            ],
        ],
        'readonly' => true,
    ],
    'cache'              => [
        'value'    => [
            'type'     => env('CACHE_TYPE', 'files'),
            'sid'      => "#01",
            'memcache' => [
                'host' => env('MEMCACHE_HOST', 'unix:///tmp/memcached.sock'),
                'port' => env('MEMCACHE_PORT', '0'),
            ],
        ],
        'readonly' => false,
    ],
    'bitrix-systemcheck' => [
        'value'    => [
            'monitorings' => [
                \System\SystemCheck\Monitorings\FullMonitoring::class,
            ],
        ],
        'readonly' => true,
    ],

    'google' => [
        'value'    => [
            'mapsApiKey' => env('GOOGLE_MAP_KEY'),
            'recaptcha'  => [
                'publicKey' => env('RECAPTCHA_PUBLIC_KEY'),
                'secretKey' => env('RECAPTCHA_SECRET_KEY'),
            ],
        ],
        'readonly' => false,
    ]
];
