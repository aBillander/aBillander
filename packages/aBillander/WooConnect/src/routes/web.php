<?php

// github.com/gocanto/gocanyo-pkg


Route::group([

	'namespace' => 'aBillander\WooConnect\Http',
	'prefix'    => 'wooc'

], function () {

//	Route::get('/', 'WooConfigurationKeysController@hello');

});


Route::group([

	'middleware' =>  ['web', 'auth', 'context'],
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

    Route::resource('wproducts', 'WooProductsController');

    Route::get('wproducts/{id}/fetch' , array('uses' => 'WooProductsController@fetch', 
                                                        'as'   => 'wproducts.fetch' ));
    Route::get('wproducts/importProductImages' , array('uses' => 'WooProductsController@importProductImages', 
                                                        'as'   => 'wproducts.import.product.images' ));


    Route::resource('wcategories', 'WooCategoriesController');

    Route::get('wcategories/{id}/fetch' , array('uses' => 'WooCategoriesController@fetch', 
                                                        'as'   => 'wcategories.fetch' ));

});

/* WooCommerce webhoohs -> todo:download Woo Order to aBillander

https://robotninja.com/blog/test-woocommerce-webhooks/

https://www.onsitewp.com/woocommerce-webhooks-secret-key/

*/


/* ********************************************************** */

// https://pineco.de/handling-webhooks-with-laravel/
// https://medium.com/team-culqi/usando-webhooks-con-laravel-1fa6d707bdba

// https://medium.com/@serhii.matrunchyk/laravel-vue-spas-how-to-send-ajax-requests-and-not-run-into-csrf-tokens-mismatch-exceptions-da3b71b287ab


Route::post('wooc/webhook/order/created', function()
{
	// we need to disable the CSRF token validation for this route. 
	// Open the VerifyCsrfToken middleware and add the route to the $except property.

	// $secret = 'Q0S,fT$~VBdg/[o`QXvQ?Zyd0B1%PX)5grt8g2B>1PFjs/BlSl';
	$secret = config('woocommerce.webhooks.order_created');

	$wc_signature = "X-WC-Webhook-Signature";

	$hookSignature = request()->headers->get($wc_signature);

	$request_body = request()->getContent();
	$payLoad = json_decode(request()->getContent(), true);


	$HashSignature = base64_encode(hash_hmac('sha256',$request_body, $secret, true));

	// Step 1: Log (ActivityLogger)
    // Start Logger
    $logger = \App\ActivityLogger::setup( 'WooCommerce Webhook Delivery', 'WooC Order Created' );
    
    if ( $HashSignature != $hookSignature )
    {
    	// Log this
    	$logger->log("ERROR", 'Signature mismatch. Payload: :payLoad', ['payLoad' => $request_body]);

    	// Die silently:
    	abort('401');
    }

    $logger->log("INFO", 'New WooCommerce Order :oID. Payload: :payLoad', ['payLoad' => $request_body, 'oID' => $payLoad['id']]);

	// Step 2: Email notification 
	$notify_to = 'lidiamartinez@laextranatural.es';
//	$notify_to = 'sdg@abillander.com';
	$notify_cc_to = 'dcomobject@hotmail.com';



        // MAIL stuff
        try {

            $user_message = 'New WooCommerce Order: '. $payLoad['id'];

            $data = array(
                'from'     => abi_mail_from_address(),          // config('mail.from.address'  ),
                'fromName' => abi_mail_from_name(),             // config('mail.from.name'    ),
                'to'       => $notify_to,	// abi_mail_from_address(),
                'toName'   => abi_mail_from_name(),
                'subject'  => ' :_> [aBillander] ' . l('WooCommerce Order Created'),

                'notify_cc_to' => $notify_cc_to,
                );

            

            $send = \Mail::send('emails.default', ['user_message' =>  $user_message], function($message) use ($data)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['notify_cc_to'] )->subject( $data['subject'] );    // Will send blind copy to sender!

            }); 

        } catch(\Exception $e) {

	    	// Log this
	    	$logger->log("ERROR", 'Could not send notification email to :notify_to. Order: :oID. Mail error: :error', ['notify_to' => $notify_to, 'oID' => $payLoad['id'], 'error' => $e->getMessage()]);

            // abi_r($e->getMessage(), true);

        }
        // MAIL stuff ENDS

/* To Do:

https://stackoverflow.com/questions/45819536/laravel-5-swiftmailers-send-how-to-get-error-code
https://stackoverflow.com/questions/19366289/swift-mailer-cant-send-mail-and-cant-find-error-logs/42221501#42221501

*/


});


