<?php

// https://laravel-news.com/creating-configuration-in-laravel
// https://stackoverflow.com/questions/38665907/how-to-add-custom-config-file-to-app-config-in-laravel-5
// Remember: php artisan config:cache

return [

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable multi-tennancy
    |--------------------------------------------------------------------------
    */
    'enable'   => true,
    
    /*
    |--------------------------------------------------------------------------
    | Allowed Tenants
    |--------------------------------------------------------------------------
    */
    'names' => ['localhost', 'cocin'],
];
