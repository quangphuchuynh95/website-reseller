<?php

return [
    'connections' => [
        'children_website' => [
            'driver' => 'mysql',
            'url' => env('DB_WEBSITE_URL'),
            'host' => env('DB_WEBSITE_HOST', '127.0.0.1'),
            'port' => env('DB_WEBSITE_PORT', '3306'),
//            'database' => env('DB_WEBSITE_DATABASE', 'laravel'),
            'username' => env('DB_WEBSITE_USERNAME', 'root'),
            'password' => env('DB_WEBSITE_PASSWORD', ''),
            'unix_socket' => env('DB_WEBSITE_SOCKET', ''),
            'charset' => env('DB_WEBSITE_CHARSET', 'utf8mb4'),
            'collation' => env('DB_WEBSITE_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'dbname_prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],
];
