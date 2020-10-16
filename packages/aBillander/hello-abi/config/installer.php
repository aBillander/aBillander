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
        'minPhpVersion' => '7.0.0'
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
        'bootstrap/cache/'       => '775',
        'storage/db_backups/'    => '775',
        'storage/export/'        => '775',
        'storage/fonts/'         => '775',
        'storage/framework/'     => '775',
        'storage/pdf/'           => '775',
        'storage/logs/'          => '775',
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
