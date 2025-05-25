<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cart Storage Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default cart storage driver that will be used
    | for storing cart items. Supported drivers: "session", "redis".
    |
    */
    'driver' => env('CART_DRIVER', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Cart Expiration Time
    |--------------------------------------------------------------------------
    |
    | This value determines how long a cart will be stored before it expires.
    | The value is in seconds. Default is 7 days.
    |
    */
    'expiration' => env('CART_EXPIRATION', 60 * 60 * 24 * 7),
];
