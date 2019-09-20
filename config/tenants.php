<?php

// https://laravel-news.com/creating-configuration-in-laravel
// https://stackoverflow.com/questions/38665907/how-to-add-custom-config-file-to-app-config-in-laravel-5
// Remember: php artisan config:cache

return [

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable multi-tenancy
    |--------------------------------------------------------------------------
    */
    'enable'   => env('TENANT_ENABLED', false),
    
    /*
    |--------------------------------------------------------------------------
    | Allowed Tenants
    |--------------------------------------------------------------------------
    */
    'names' => explode(',', env('TENANT_NAMES', ['localhost'])),
];
