<?php

// github.com/gocanto/gocanyo-pkg


Route::group([

	'namespace' => 'aBillander\WooConnect\Http',
	'prefix'    => 'wooc'

], function () {

//	Route::get('/', 'WooConfigurationKeysController@hello');

});


Route::group([

	'middleware' =>  ['web', 'auth'],
	'namespace' => 'aBillander\WooConnect\Http\Controllers',
	'prefix'    => 'wooc'

], function () {

//	Route::get('orders', ['as' => 'worders', 'uses' => 'WooOrdersController@index']);
//	Route::get('orders/statuses', 'WooOrdersController@getStatuses');	// Semms this endpoint does not exist /!\
//	Route::get('orders/{id}', 'WooOrdersController@show');
//	Route::post('orders/{id}', ['as' => 'wostatus', 'uses' => 'WooOrdersController@update']);

	Route::resource('wooconnect/wooconfigurationkeys', 'WooConfigurationKeysController');


	Route::get( 'wooconnect/configuration', 'WooConfigurationKeysController@configurationsEdit')
			->name('wooconnect.configuration');
	Route::post('wooconnect/configuration', 'WooConfigurationKeysController@configurationsUpdate')
			->name('wooconnect.configuration.update');

	Route::get( 'wooconnect/configuration/taxes', 'WooConfigurationKeysController@configurationTaxesEdit')
			->name('wooconnect.configuration.taxes');
	Route::post('wooconnect/configuration/taxes', 'WooConfigurationKeysController@configurationTaxesUpdate')
			->name('wooconnect.configuration.taxes.update');

	Route::get( 'wooconnect/configuration/paymentgateways', 'WooConfigurationKeysController@configurationPaymentGatewaysEdit')
			->name('wooconnect.configuration.paymentgateways');
	Route::post('wooconnect/configuration/paymentgateways', 'WooConfigurationKeysController@configurationPaymentGatewaysUpdate')
			->name('wooconnect.configuration.paymentgateways.update');

	Route::get( 'wooconnect/configuration/shippingmethods', 'WooConfigurationKeysController@configurationShippingMethodsEdit')
			->name('wooconnect.configuration.shippingmethods');
	Route::post('wooconnect/configuration/shippingmethods', 'WooConfigurationKeysController@configurationShippingMethodsUpdate')
			->name('wooconnect.configuration.shippingmethods.update');
//

    Route::resource('worders', 'WooOrdersController');

    Route::post('worders/importOrders' , array('uses' => 'WooOrdersController@importOrders', 
                                                        'as'   => 'worders.import.orders' ));
    Route::get('worders/{id}/import' , array('uses' => 'WooOrdersController@import', 
                                                        'as'   => 'worders.import' ));
    Route::get('worders/{id}/fetch' , array('uses' => 'WooOrdersController@fetch', 
                                                        'as'   => 'worders.fetch' ));
    Route::get('worders/{id}/invoice', array('uses' => 'WooOrdersController@invoice', 
                                                        'as'   => 'worders.invoice' ));

});

