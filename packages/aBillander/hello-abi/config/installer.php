<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */
    'core' => [
        'minPhpVersion' => '8.0.0'
    ],
    'final' => [
        'key' => true,
        'publish' => false
    ],
    'requirements' => [
        'php' => [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            'JSON',
            'cURL',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        'bootstrap/cache/'       => '755',
        'storage/'               => '755',
        'storage/db_backups/'    => '755',
        'storage/export/'        => '755',
        'storage/fonts/'         => '755',
        'storage/framework/'     => '755',
        'storage/pdf/'           => '755',
        'storage/logs/'          => '755',
        'storage/tenants/'       => '755',
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | These are the languages supported by the installer.
    | This configuration only affects this package.
    |
    */
    'supportedLocales' => [
        'en' => ['name' => 'English',    'native' => 'English'],
        'es' => ['name' => 'Spanish',    'native' => 'Español'],
 //       'ca' => ['name' => 'Catalan',    'native' => 'Català'],
 //       'fr' => ['name' => 'French',     'native' => 'Français'],
 //       'de' => ['name' => 'German',     'native' => 'Deutsch'],
 //       'it' => ['name' => 'Italian',    'native' => 'Italiano'],
 //       'pt' => ['name' => 'Portuguese', 'native' => 'Português'],
    ],


];
