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


        // tinyCRM
        Route::get('/crm/home', 'CRMHomeController@index')->name('crm.home');
        
        Route::resource('parties',          'PartiesController');
        Route::resource('parties.contacts', 'ContactsController');

        Route::resource('leads',           'LeadsController');
        Route::get('leads/create/withparty/{party}', 'LeadsController@createWithParty')->name('leads.create.withparty');
        Route::get('leads/parties/{party}',  'LeadsController@indexByCustomer')->name('party.leads');

        Route::resource('leads.leadlines', 'LeadLinesController');
