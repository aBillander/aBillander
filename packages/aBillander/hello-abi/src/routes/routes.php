<?php

$installerGroup =
[
    'prefix' => 'install',
    'as' => 'installer::',
    'namespace' => 'aBillander\Installer\Controllers',
    'middleware' => ['web', 'installer'],
];

Route::group($installerGroup, function () {

    Route::get('/', 'WelcomeController@welcome')->name('welcome');
    Route::post('/', 'WelcomeController@setLocale');

    Route::get('/license', 'LicenseController@show')->name('license');
    Route::post('/license', 'LicenseController@accept');

    Route::get('/requirements', 'RequirementsController@check')->name('requirements');

    Route::get('/configuration', 'ConfigurationController@show')->name('configuration');
    Route::post('/configuration', 'ConfigurationController@save');

    Route::get('/mail', 'MailConfigurationController@show')->name('mail');
    Route::post('/mail', 'MailConfigurationController@save');

    Route::get('/run', 'RunInstallController@show')->name('install');
    Route::post('/run', 'RunInstallController@run');

    Route::get('/company', 'CompanyController@show')->name('company');
    Route::post('/company', 'CompanyController@store');

    Route::get('/done', 'FinalController@show')->name('done');

    Route::get('/countries/{countryId}/getstates', 'CountriesController@getStates');

});
