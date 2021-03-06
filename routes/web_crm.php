<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Secure-Routes
Route::group(['middleware' =>  ['restrictIp', 'auth', 'context']], function()
{
        // microCRM
        Route::get('/crm/home', 'CRMHomeController@index')->name('crm.home');
        
        Route::resource('parties',  'PartiesController');

        Route::resource('contacts',                     'ContactsController');
        Route::get('contacts/create/withparty/{party}', 'ContactsController@createWithParty')->name('contacts.create.withparty');
        Route::get('contacts/parties/{party}',          'ContactsController@indexByParty'   )->name('party.contacts');

        Route::resource('leads',                     'LeadsController');
        Route::get('leads/create/withparty/{party}', 'LeadsController@createWithParty')->name('leads.create.withparty');
        Route::get('leads/parties/{party}',          'LeadsController@indexByParty'   )->name('party.leads');

        Route::resource('leads.leadlines', 'LeadLinesController');

});
