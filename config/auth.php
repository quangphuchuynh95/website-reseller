<?php

return [
    'guards' => [
        'wr_customer' => [
            'driver' => 'session',
            'provider' => 'wr_customers',
        ],
    ],

    'providers' => [
        'wr_customers' => [
            'driver' => 'eloquent',
            'model' => QuangPhuc\WebsiteReseller\Models\Customer::class,
        ],
    ],

    'passwords' => [
        'wr_customers' => [
            'provider' => 'wr_customers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];
