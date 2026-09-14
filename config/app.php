<?php

return [
    'name'     => env('APP_NAME', 'e-Sarpras'),
    'env'      => env('APP_ENV', 'local'),
    'debug'    => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
    'version'  => '1.0.0',
];