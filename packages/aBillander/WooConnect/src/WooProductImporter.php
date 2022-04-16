<?php 

namespace aBillander\WooConnect;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use App\Context;
use App\Configuration;
use App\Product;
use App\Image;
use App\Combination;
use App\Tax;
use App\ActivityLogger;

// use \aBillander\WooConnect\WooProduct;

class WooProductImporter {

	protected $wc_product;
	protected $run_status = true;		// So far, so good. Can continue export
	protected $error = null;

	protected $raw_data = array();

	protected $currency;				// aBillander Object
	protected $customer;				// aBillander Object
	protected $product;					// aBillander Object
	protected $invoicing_address;		// aBillander Object
	protected $shipping_address;		// aBillander Object
	protected $invoicing_address_id;
	protected $shipping_address_id;

	protected $next_sort_product = 0;

	// Logger to send messages
	protected $log;
	protected $logger;
	protected $log_has_errors = false;

    public function __construct ($product_id = null, Product $product = null)
    {
        // Get logger
        // $this->log = $rwsConnectorLog;

        $this->product = $product;

        $this->run_status = true;


        // Start Logger
//        $this->logger = \App\ActivityLogger::setup( 
//            'Import WooCommerce Products', self::loggerSignature() );			//  :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')

        $this->logger = self::loggerSetup();


        // Get product data (if product exists!)
        if ( intval($product_id) ) {
            // set the product
            // $this->fill_in_data( intval($product_id) );

            // fill it, parse it and save it
            // $this->populate_data();
            
            // $this->import();  // The whole process

			$this->fill_in_data( intval($product_id) );
            // return true

        } else {
            $this->logMessage( 'ERROR', 'El número de Producto <b>"'.$product_id.'"</b>no es válido.' );
            $this->run_status = false;
        }
    }


    public static function loggerName() 
    {
    	return 'Import WooCommerce Products';
    }

    public static function loggerSignature() 
    {
    	return __CLASS__;
    }

    public static function loggerSetup() 
    {
    	return ActivityLogger::setup( 
                self::loggerName(), 							//  :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                self::loggerSignature() )
            ->backTo( route('wproducts.index') );
    }

    public static function logger() 
    {
    	return self::loggerSetup();
    }
    
    /**
     *   Data retriever & Transformer
     */
    public function fill_in_data($product_id = null)
    {
        
        // 
    	// Get $product_id data...
//        $data = $this->raw_data = WooConnector::getWooProduct( intval($product_id) );
        $data = $this->raw_data = WooProduct::fetchById( $product_id );
        if (!$data) {
            $this->logMessage( 'ERROR', 'Se ha intentado recuperar el Producto número <b>"'.$product_id.'"</b> y no existe.' );
            $this->run_status = false;
        } else {
            // Are we allowed?
            $valid_types = ['simple', 'grouped', 'variable'];     //  external not allowed
            if ( !in_array($data['type'], $valid_types) ) {
                $this->logMessage( 'ERROR', 'El Producto número <b>"'.$product_id.'"</b> no se puede descargar porque es de un tipo no permitido: ('.$data['type'].').' );
                $this->run_status = false;
            }

            // Should have SKU
            if ( $data['type'] == '' ) {
                $this->logMessage( 'ERROR', 'El Producto número <b>"'.$product_id.'"</b> no se puede descargar porque el campo SKU está vacío, o no tiene un valor válido: ('.$data['sku'].').' );
                $this->run_status = false;
            }

            // Already downloaded?
            $abi_product = Product::where('reference', $data['sku'])->first();
            if ( $abi_product ) {
                $this->logMessage( 'ERROR', 'El Producto número <b>"'.$product_id.'"</b> ya se descargó: '.$abi_product->reference.' ('.$abi_product->name.').' );
                $this->run_status = false;
            }

        	// Data transformers
        	;

        }

        return $this->run_status;
    }
    
    public function getNextLineSortProduct()
    {
        $this->next_sort_product += 10;

        return $this->next_sort_product;
    }


    public static function processProduct( $product_id = null ) 
    {
        $importer = new static($product_id);
        if ( !$importer->tell_run_status() ) 
        {
        	$importer->logMessage("ERROR", l('Product number <span class="log-showoff-format">{$pid}</span> could not be loaded.'), ['$pid' => $product_id]);

        	return $importer;
        }

//        abi_r($importer->raw_data, true);

        // Do stuff
        $product = $importer->raw_data;

        $data = [
            'product_type' => $product['type'] == 'variable' ? 'combinable' : $product['type'],   // Product type. Options: simple, grouped, external and variable. Default is simple.
            'procurement_type' => 'none',
            'mrp_type' => 'manual',

            'name' => $product['name'],
//            'position'
            'reference' => $product['sku'],
//            'ean13',
            'description' => $product['description'],
            'description_short' => $product['short_description'],

            'price' => $product['regular_price'],
            'price_tax_inc' => $product['regular_price'],   // This will be set later

            'width'  => $product['dimensions']['width'], 
            'height' => $product['dimensions']['height'],
            'depth'  => $product['dimensions']['length'],
            'weight' => $product['weight'],

//            'notes',
            'stock_control' => (int) $product['manage_stock'] > 0 ? 1 : 0,

            'publish_to_web' => 1,
            'webshop_id' => $product['id'],
            'blocked' => 0,
            'active' => 1,
            
            'tax_id' => Configuration::getInt('DEF_TAX'),
//            'ecotax_id'
            'measure_unit_id' => Configuration::getInt('DEF_MEASURE_UNIT_FOR_PRODUCTS'),
            'category_id' => Configuration::getInt('DEF_CATEGORY'),
        ];

        // Taxes stuff
        // Dictionary
        $dic_taxes = json_decode(Configuration::get('WOOC_TAXES_DICTIONARY_CACHE'), true);
        // $dic_taxes = json_decode('{"standard":"1","reduced-rate":"2","r-e":"2"}', true);

        $key = $product['tax_class'] == '' ? 'standard' : $product['tax_class'];

        if ($dic_taxes && array_key_exists($key, $dic_taxes))
            $data['tax_id'] = (int) $dic_taxes[$key];

        // Price with Tax
        $tax_percent = Tax::findOrFail( $data['tax_id'] )->percent;

        $data['price_tax_inc'] = $data['price'] * (1.0 + $tax_percent/100.0);


        // Good boy: Create Product
        $abi_product = Product::create( $data );

        $importer->logMessage("INFO", 'Se ha creado el Producto: <span class="log-showoff-format">{pid}</span> <a class="btn btn-sm btn-warning" href="{route}" title="Ir a" target="_blank"><i class="fa fa-pencil"></i></a> .', ['pid' => $abi_product->id, 'route' => route('products.edit', $abi_product->id)]);


        // Images stuff

            $images = $product['images'] ?? [];

            if ( $images && count($images) )
            {
                // Initialize with something to show
                // $img_src  = $images[0]['src']  ?? '';
                // $img_name = $images[0]['name'] ?? '';
                // $img_alt  = $images[0]['alt']  ?? '';

                foreach ($images as $position => $image)
                {
                    $img_src  = $image['src'];
                    $img_name = $image['name'];
                    $img_alt  = $image['alt'];

                    $img_position  = $position; // position = 0 => Product Image (Woo Featured image)

                    $img_alt = ( $img_alt != '' ? ' :: ' . $img_alt : '' );
                    $caption = $img_name . $img_alt;

                    // Make the magic
                    if( $img_src )
                    {

                        $image = Image::createForProductFromUrl($img_src, ['caption' => $caption]);
                        
                        $abi_product->images()->save($image);

                        if ( $position == 0 )
                            $abi_product->setFeaturedImage( $image );

                    }
                }

            } else {

                // Maybe a default image here???
                // $img_src = 'https://<wp_site>/wp-content/plugins/woocommerce/assets/images/placeholder.png';
                
            }

        $importer->logMessage("INFO", 'Se han añadido Imagenes al Producto: <span class="log-showoff-format">{pid}</span> <a class="btn btn-sm btn-warning" href="{route}" title="Ir a" target="_blank"><i class="fa fa-pencil"></i></a> ({imgs}).', ['pid' => $abi_product->id, 'imgs' => count($images), 'route' => route('products.edit', $abi_product->id)]);


		// Good boy: final touches here


        return $importer;
    }

    
    

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


/* ********************************************************************************************* */   


    public function tell_run_status() {
      return $this->run_status;
    }
    
    protected function logMessage($level = '', $message = '', $context = [])
    {
        $this->logger->log($level, $message, $context);

        if ( $level == 'ERROR' ) $this->log_has_errors = true;
    }
    
    protected function logInfo($message = '', $context = [])
    {
        $this->logMessage('INFO', $message, $context);
    }
    
    protected function logWarning($message = '', $context = [])
    {
        $this->logMessage('WARNING', $message, $context);
    }
    
    protected function logError($message = '', $context = [])
    {
        $this->logMessage('ERROR', $message, $context);
    }
    
    protected function logTimer($message = '', $context = [])
    {
        $this->logMessage('TIMER', $message, $context);
    }
    
    public function logHasErrors()
    {
        return $this->log_has_errors; 
    }
    
    public function logView()
    {
        return $this->log; 
    }
}