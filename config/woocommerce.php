<?php
/*
    WooCommerce: 

    https://github.com/woocommerce/wc-api-php/issues/10
    Error: Invalid JSON returned => Well, when you will change your permalink settings , Plain to Post name, this error will disappear. so change your site's permalink setting, dont use default setting

    Use .hraccess as suggested by WordPress
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Home URL to the store you want to connect to here
    |--------------------------------------------------------------------------
    */
    'store_url' => env('WC_STORE_URL', 'https://www.mywoostore.com/'),

    /*
    |--------------------------------------------------------------------------
    | Consumer Key 
    |--------------------------------------------------------------------------
    */
    'consumer_key' => env('WC_CONSUMER_KEY', 'ck_your_consumer_key'),

    /*
    |--------------------------------------------------------------------------
    | Consumer Secret
    |--------------------------------------------------------------------------
    */
    'consumer_secret' => env('WC_CONSUMER_SECRET', 'cs_your_consumer_secret'),

    /*
    |--------------------------------------------------------------------------
    | SSL support
    |--------------------------------------------------------------------------
    */
    'verify_ssl' => env('WC_VERIFY_SSL', false),

    /*
    |--------------------------------------------------------------------------
    | API version
    |--------------------------------------------------------------------------
    */
    'api_version' => env('WC_VERSION', 'v3'),

    /*
    |--------------------------------------------------------------------------
    | WP API usage
    |--------------------------------------------------------------------------
    */
    'wp_api' => env('WC_WP_API', true),

    /*
    |--------------------------------------------------------------------------
    | Force Basic Authentication as query string
    |--------------------------------------------------------------------------
    */
    'query_string_auth' => env('WC_WP_QUERY_STRING_AUTH', false),

    /*
    |--------------------------------------------------------------------------
    | WP timeout
    |--------------------------------------------------------------------------
    */
    'timeout' => env('WC_WP_TIMEOUT', 15),


    /*
    |--------------------------------------------------------------------------
    | WP Webhooks
    |--------------------------------------------------------------------------
    */
    'webhooks' => [
                        'order_created'   => env('WC_WEBHOOK_SECRET_ORDER_CREATED',   'order_created_secret_key'  ),
                        'product_updated' => env('WC_WEBHOOK_SECRET_PRODUCT_UPDATED', 'product_updated_secret_key'),
                ],
];
