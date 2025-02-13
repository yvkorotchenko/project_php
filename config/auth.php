<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

//        'api' => [
//            'driver' => 'token',
//            'provider' => 'users',
//            'hash' => false,
//        ],
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
            'hash' => false,
        ],
        'api-operators-chat' => [
            'driver' => 'jwt',
            'provider' => 'chat-operators',
            'hash' => false,
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
//            'model' => App\Models\User::class,
            'model' => App\Laravue\Models\User::class,
        ],
        'chat-operators' => [
            'driver' => 'eloquent',
            'model' => App\Chat\Models\CustomerServiceOperator::class,
        ],
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    'password_timeout' => 10800,

];
