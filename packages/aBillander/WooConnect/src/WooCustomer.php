<?php

namespace aBillander\WooConnect;

// use Illuminate\Database\Eloquent\Model;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

class WooCustomer // extends Model
{
//    protected $dates = ['deleted_at', 'date_created', 'date_paid', 'date_abi_exported', 'date_invoiced'];
	
//    protected $fillable = [ ];

//    protected $guarded = [];

    protected $data = [];
    protected $run_status = true;       // So far, so good. Can continue export
    protected $error = null;

    
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    
    // See: https://github.com/laravel-enso/DataImport

    public function __construct ($id = null)
    {

        $this->run_status = true;

        // Get customer data (if customer exists!)

        if ( $id !== null ) {

            // Get Customer fromm WooCommerce Shop
            try {

                $data = WooCommerce::get('customers/' . $id); // Array

                $this->data = $data;
            }

            catch( WooHttpClientException $e ) {

                /*
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                */

                $this->data = [];
                // So far, we do not know if customer_id does not exist, or connection fails. 
                // does it matter? -> Anyway, no customer is issued

            }

            if (!$this->data) {
                $this->error[] = 'Se ha intentado recuperar el Cliente <b>"'.$id.'"</b> y no existe.';
                $this->run_status = false;
            }

        }

    }

    public static function fetch( $id = null )
    {
        $customer = new static($id);
        // if (!$customer->tell_run_status()) 
        return $customer->data;
    }


    public static function fetchAll( $fields = ['id'], $per_page = 10 )
    {
        $customers = collect([]);

        $page = 1;

        $params = [
//          'context',              // Scope under which the request is made; determines fields present in response. Options: view and edit. Default is view.
            'per_page' => $per_page, // Default: 10 :: Maximum: 100
            'page' => $page,
//          'search' =>             // Limit results to those matching a string.
//          'exclude'   array   Ensure result set excludes specific IDs.
//          'include'   array   Limit result set to specific IDs.
//          'offset'    integer     Offset the result set by a specific number of items.
            'orderby' => 'registered_date', // Sort collection by object attribute. Options: id, include, name and registered_date. Default is name.
            'order'   => 'desc',        // Options: asc and desc. Default is asc.
//          'email',
//          'role',
        ];


        try {

            $results = WooCommerce::get('customers', $params);

        }

            catch(WooHttpClientException $e) {

//          $e->getMessage(); // Error message.

//          $e->getRequest(); // Last request data.

//          $e->getResponse(); // Last response data.
//          abi_r($e->getResponse);

            return $e;

            $err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

            // Improbe this: 404 doesnot show upper-left logo , and language is English
            return redirect('404')
                ->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

        }
        
        // So far so good, then
        $total = WooCommerce::totalResults();

        $customers = $customers->merge($results);

        // Are there more pages?
        $totalPages = ceil($total / $per_page);
        $leftPages = $totalPages - 1;

        if ( $leftPages > 0 )
        for ($i=2; $i <= $totalPages; $i++) { 
            # code...
                $params = [
    //          'context',              // Scope under which the request is made; determines fields present in response. Options: view and edit. Default is view.
                'per_page' => $per_page, // Default: 10 :: Maximum: 100
                'page' => $i,
    //          'search' =>             // Limit results to those matching a string.
    //          'exclude'   array   Ensure result set excludes specific IDs.
    //          'include'   array   Limit result set to specific IDs.
    //          'offset'    integer     Offset the result set by a specific number of items.
                'orderby' => 'registered_date', // Sort collection by object attribute. Options: id, include, name and registered_date. Default is name.
                'order'   => 'desc',        // Options: asc and desc. Default is asc.
    //          'email',
    //          'role',
            ];


            try {

                $results = WooCommerce::get('customers', $params);

            }

                catch(WooHttpClientException $e) {

    //          $e->getMessage(); // Error message.

    //          $e->getRequest(); // Last request data.

    //          $e->getResponse(); // Last response data.
    //          abi_r($e->getResponse);

                return $e;

            }
            
            // So far so good, then

            $customers = $customers->merge($results);
        }

        $ids = $customers->pluck('id')->toArray();

        return $ids;

    }


    public static function fetchAllEmail( $fields = ['email'], $per_page = 10 )
    {
        $customers = collect([]);

        $page = 1;

        $params = [
//          'context',              // Scope under which the request is made; determines fields present in response. Options: view and edit. Default is view.
            'per_page' => $per_page, // Default: 10 :: Maximum: 100
            'page' => $page,
//          'search' =>             // Limit results to those matching a string.
//          'exclude'   array   Ensure result set excludes specific IDs.
//          'include'   array   Limit result set to specific IDs.
//          'offset'    integer     Offset the result set by a specific number of items.
            'orderby' => 'registered_date', // Sort collection by object attribute. Options: id, include, name and registered_date. Default is name.
            'order'   => 'desc',        // Options: asc and desc. Default is asc.
//          'email',
//          'role',
        ];


        try {

            $results = WooCommerce::get('customers', $params);

        }

            catch(WooHttpClientException $e) {

//          $e->getMessage(); // Error message.

//          $e->getRequest(); // Last request data.

//          $e->getResponse(); // Last response data.
//          abi_r($e->getResponse);

            return $e;

            $err = '<ul><li><strong>'.$e->getMessage().'</strong></li></ul>';

            // Improbe this: 404 doesnot show upper-left logo , and language is English
            return redirect('404')
                ->with('error', l('La Tienda Online ha rechazado la conexión, y ha dicho: ') . $err);

        }
        
        // So far so good, then
        $total = WooCommerce::totalResults();

        $customers = $customers->merge($results);

        // Are there more pages?
        $totalPages = ceil($total / $per_page);
        $leftPages = $totalPages - 1;

        if ( $leftPages > 0 )
        for ($i=2; $i <= $totalPages; $i++) { 
            # code...
                $params = [
    //          'context',              // Scope under which the request is made; determines fields present in response. Options: view and edit. Default is view.
                'per_page' => $per_page, // Default: 10 :: Maximum: 100
                'page' => $i,
    //          'search' =>             // Limit results to those matching a string.
    //          'exclude'   array   Ensure result set excludes specific IDs.
    //          'include'   array   Limit result set to specific IDs.
    //          'offset'    integer     Offset the result set by a specific number of items.
                'orderby' => 'registered_date', // Sort collection by object attribute. Options: id, include, name and registered_date. Default is name.
                'order'   => 'desc',        // Options: asc and desc. Default is asc.
    //          'email',
    //          'role',
            ];


            try {

                $results = WooCommerce::get('customers', $params);

            }

                catch(WooHttpClientException $e) {

    //          $e->getMessage(); // Error message.

    //          $e->getRequest(); // Last request data.

    //          $e->getResponse(); // Last response data.
    //          abi_r($e->getResponse);

                return $e;

            }
            
            // So far so good, then

            $customers = $customers->merge($results);
        }

        $ids = $customers->pluck('email')->toArray();

        return $ids;

    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function cosito()
    {
        // return $this->hasOne('App\Customer', 'webshop_id', 'customer_id');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function getRawData( )
    {
        return $customer->data;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
/*    
    // Custom function
    public static function getProductReference( $product = [] )
    {
		$r = '';

		if ( isset( $product['id'] ) ) {
			$r = 'WooC #'.$product['id'];
		}
		
		return $r;
    }

    public static function getMetaByKey( $product = [], $key = '' )
    {
		$vn = '';

		if ( isset( $product['meta_data'] ) && $key ) {
			foreach($product['meta_data']  as $data ) {
				if( $data['key'] == $key ) {
					$vn = $data['value'];
					break;
				}
			}
		}
		
		return $vn;
    }
*/    
    
    /*
    |--------------------------------------------------------------------------
    | Transformers
    |--------------------------------------------------------------------------
    */
    
/*    {
        if ( !isset( $product['id'] ) ) return $product;

        list($product["date_created_date"], $product["date_created_time"]) = explode(' ', self::getDate( $product["date_created"] . ' ' ));

        $product["date_created"] = self::getDateShort( $product["date_created"] );

        $product["date_paid"]    = self::getDateShort( $product["date_paid"] );

        $product["date_abi_exported"] = self::getDateShort( self::getAbiExportedDate( $product ) );

        $product["date_abi_invoiced"] = self::getDateShort( self::getAbiInvoicedDate( $product ) );

        // To Do: Country & State & maybe more...

        return $product;
    }
*/
}
