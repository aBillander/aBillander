<?php

namespace aBillander\WooConnect;

// use Illuminate\Database\Eloquent\Model;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

class WooCategory // extends Model
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

        // Get category data (if category exists!)

        if ( $id !== null ) {

            // Get Category fromm WooCommerce Shop
            try {

                $data = WooCommerce::get('products/categories/' . $id); // Array

                $this->data = $data;
            }

            catch( WooHttpClientException $e ) {

                /*
                $e->getMessage(); // Error message.

                $e->getRequest(); // Last request data.

                $e->getResponse(); // Last response data.
                */

                $this->data = [];
                // So far, we do not know if category_id does not exist, or connection fails. 
                // does it matter? -> Anyway, no category is issued

            }

            if (!$this->data) {
                $this->error[] = 'Se ha intentado recuperar la Categoría <b>"'.$id.'"</b> y no existe.';
                $this->run_status = false;
            }

        }

    }

    public static function fetch( $id = null )
    {
        $category = new static($id);
        // if (!$category->tell_run_status()) 
        return $category->data;
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
        return $category->data;
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
