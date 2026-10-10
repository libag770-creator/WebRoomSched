<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most Blade templates are compiled into PHP files for better performance.
    | This path must exist and be writable by the web server/PHP process.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views')) ?: storage_path('framework/views')
    ),

    'relative_hash' => false,

    'cache' => true,

    'compiled_extension' => 'php',

    'check_cache_timestamps' => true,
];
