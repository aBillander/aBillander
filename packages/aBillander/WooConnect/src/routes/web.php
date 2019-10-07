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
    Route::get('wproducts/import/ProductImages' , array('uses' => 'WooProductsController@importProductImages', 
                                                        'as'   => 'wproducts.import.product.images' ));
    Route::get('wproducts/import/ProductDescriptions' , array('uses' => 'WooProductsController@importProductDescriptions', 
                                                        'as'   => 'wproducts.import.product.descriptions' ));


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
	$notify_to = 'laextranatural@laextranatural.es';
//	$notify_to = 'sdg@abillander.com';
	$notify_cc_to = 'dcomobject@hotmail.com';


    if ( $payLoad['status'] != 'processing' )
        return ;



        // MAIL stuff
        try {

            $user_message = 'New WooCommerce Order: '. $payLoad['id'];

            $data = array(
                'from'     => abi_mail_from_address(),          // config('mail.from.address'  ),
                'fromName' => abi_mail_from_name(),             // config('mail.from.name'    ),
                'to'       => $notify_to,	// abi_mail_from_address(),
                'toName'   => abi_mail_from_name(),
                'subject'  => ' :_> [aBillander] - ' . $payLoad['id'] .' - '. l('WooCommerce Order Created').' ['.$payLoad['status'].']',

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


/*

Array
(
    [id] => 5640
    [parent_id] => 0
    [number] => 5640
    [order_key] => wc_order_5d76cef755c3b
    [created_via] => checkout
    [version] => 3.1.1
    [status] => pending
    [currency] => EUR
    [date_created] => 2019-09-09T22:15:19
    [date_created_gmt] => 2019-09-09T22:15:19
    [date_modified] => 2019-09-09T22:15:19
    [date_modified_gmt] => 2019-09-09T22:15:19
    [discount_total] => 0.00
    [discount_tax] => 0.00
    [shipping_total] => 0.00
    [shipping_tax] => 0.00
    [cart_tax] => 5.35
    [total] => 58.83
    [total_tax] => 5.35
    [prices_include_tax] => 
    [customer_id] => 163
    [customer_ip_address] => 213.194.146.126
    [customer_user_agent] => mozilla/5.0 (windows nt 10.0; win64; x64; rv:68.0) gecko/20100101 firefox/68.0
    [customer_note] => 
    [billing] => Array
        (
            [first_name] => M Rosa
            [last_name] => Zamora Cobo
            [company] => 
            [address_1] => C/ Juan XXIII N. 8
            [address_2] => 
            [city] => La Puebla del Río
            [state] => SE
            [postcode] => 41130
            [country] => ES
            [email] => shorbydos@hotmail.com
            [phone] => 637353533
        )

    [shipping] => Array
        (
            [first_name] => M Rosa
            [last_name] => Zamora Cobo
            [company] => 
            [address_1] => C/ Santa María, 5 Edificio Multiusos
            [address_2] => 
            [city] => La Puebla del Río
            [state] => SE
            [postcode] => 41130
            [country] => ES
        )

    [payment_method] => redsys
    [payment_method_title] => Tarjeta
    [transaction_id] => 
    [date_paid] => 
    [date_paid_gmt] => 
    [date_completed] => 
    [date_completed_gmt] => 
    [cart_hash] => 8e5a9f749f1cd1441c089a87cf8602a9
    [meta_data] => Array
        (
        )

    [line_items] => Array
        (
            [0] => Array
                (
                    [id] => 5748
                    [name] => Pan de Arroz con masa madre ECO SG pack 4 uds
                    [product_id] => 4506
                    [variation_id] => 0
                    [quantity] => 14
                    [tax_class] => 10
                    [subtotal] => 53.48
                    [subtotal_tax] => 5.35
                    [total] => 53.48
                    [total_tax] => 5.35
                    [taxes] => Array
                        (
                            [0] => Array
                                (
                                    [id] => 4
                                    [total] => 5.348
                                    [subtotal] => 5.348
                                )

                        )

                    [meta_data] => Array
                        (
                        )

                    [sku] => 4001
                    [price] => 3.82
                )

        )

    [tax_lines] => Array
        (
            [0] => Array
                (
                    [id] => 5750
                    [rate_code] => ES-IMPUESTO-1
                    [rate_id] => 4
                    [label] => Impuesto
                    [compound] => 
                    [tax_total] => 5.35
                    [shipping_tax_total] => 0.00
                    [meta_data] => Array
                        (
                        )

                )

        )

    [shipping_lines] => Array
        (
            [0] => Array
                (
                    [id] => 5749
                    [method_title] => Envío gratuito
                    [method_id] => advanced_free_shipping
                    [total] => 0.00
                    [total_tax] => 0.00
                    [taxes] => Array
                        (
                        )

                    [meta_data] => Array
                        (
                        )

                )

        )

    [fee_lines] => Array
        (
        )

    [coupon_lines] => Array
        (
        )

    [refunds] => Array
        (
        )

)

*/
