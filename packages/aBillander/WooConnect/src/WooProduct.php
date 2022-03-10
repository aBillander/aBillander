<?php

namespace aBillander\WooConnect;

// use Illuminate\Database\Eloquent\Model;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

class WooProduct // extends Model
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

    public function __construct ($sku = null)
    {

        $this->run_status = true;

        // Get product data (if product exists!)

        if ( $sku !== null ) {
            
            // Do the Mambo!!!
            $params = [

                'sku'   => $sku,

            ];

            // Get Product fromm WooCommerce Shop
            try {

                $data = WooCommerce::get('products', $params); // Array

                // get first item only
                $this->data = count($data) > 0 ?
                                $data[0]       :
                                []             ;
            }

            catch( WooHttpClientException $e ) {

                /*
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                */

                $this->data = [];
                // So far, we do not know if product_id does not exist, or connection fails. 
                // does it matter? -> Anyway, no product is issued

            }

            if (!$this->data) {
                $this->error[] = 'Se ha intentado recuperar el Producto <b>"'.$sku.'"</b> y no existe.';
                $this->run_status = false;
            }

        }

    }

    public static function fetch( $sku = null )
    {
        $product = new static($sku);
        // if (!$product->tell_run_status()) 
        return $product->data;
    }

    public static function fetchById( $id = null )
    {
        if ( $id != null ) {
            
            // Do the Mambo!!!
            $params = [

                'id'   => $id,

            ];

            // Get Product fromm WooCommerce Shop
            try {

                $product = WooCommerce::get('products/'.$id); // Array
                
            }

            catch( WooHttpClientException $e ) {

                /*
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                */

                $product = [];
                // So far, we do not know if product_id does not exist, or connection fails. 
                // does it matter? -> Anyway, no product is issued

            }
        
            return $product;

        }

        return [];
    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function cosito()
    {
        // return $this->hasOne('App\Models\Customer', 'webshop_id', 'customer_id');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function getRawData( )
    {
        return $product->data;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    
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
    
    
    /*
    |--------------------------------------------------------------------------
    | Transformers
    |--------------------------------------------------------------------------
    */
    
    public static function viewIndexTransformer( $product = [] )
    {
        if ( !isset( $product['id'] ) ) return $product;

        list($product["date_created_date"], $product["date_created_time"]) = explode(' ', self::getDate( $product["date_created"] . ' ' ));

        $product["date_created"] = self::getDateShort( $product["date_created"] );

        $product["date_paid"]    = self::getDateShort( $product["date_paid"] );

        $product["date_abi_exported"] = self::getDateShort( self::getAbiExportedDate( $product ) );

        $product["date_abi_invoiced"] = self::getDateShort( self::getAbiInvoicedDate( $product ) );

        // To Do: Country & State & maybe more...

        return $product;
    }
}
