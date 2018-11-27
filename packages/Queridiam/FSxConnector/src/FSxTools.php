<?php 

namespace Queridiam\FSxConnector;

use \App\Configuration;

class FSxTools
{

    /** @var array Configuration key Groups */
    public static $_key_groups = [

                1 => [

//                        'FSX_IMPERSONATE_TIMEOUT' => '0',,
//                      'FSX_TIME_OFFSET'               => '3',
//                      'FSX_MAX_ROUNDCYCLES'           => '50',

                        'FSOL_WEB_CUSTOMER_CODE_BASE', //      => 50000,
//                      'FSOL_WEB_GUEST_CODE_BASE'        => 60000,     // 'WOOC_ENABLE_GUEST_CHECKOUT'
                        'FSOL_ABI_CUSTOMER_CODE_BASE', //      => 80000,


                        'FSOL_CBDCFG', //  => '/public_html/laextranatural.com/wp-content/plugins/FSx-Connector/fsweb/BBDD/',
                        'FSOL_CIACFG', //  => 'imagenes/',
                        'FSOL_CPVCFG', //  => 'npedidos/',
                        'FSOL_CCLCFG', //  => 'nclientes/',
                        'FSOL_CBRCFG', //  => 'factusolweb.sql',


//    'WOO_ORDER_TO_DOWNLOAD_STATUS_ID'  => '-1',
//    'WOO_ORDER_DOWNLOADED_STATUS_ID'   => '-1',
                        'FSX_FORCE_CUSTOMERS_DOWNLOAD', //     => '0',
                        'FSX_DLOAD_CUSTOMER_SHIPPING_ADDRESS', // => '0',
//    'WOO_USE_WEB_DESC'                 => '0',
                          'FSX_ORDER_LINES_REFERENCE_CHECK', //  => '0',

                    ],

                2 => [

            'FSOL_IMPUESTO_DIRECTO_TIPO_1', //  => '',
            'FSOL_IMPUESTO_DIRECTO_TIPO_2', //  => '',
            'FSOL_IMPUESTO_DIRECTO_TIPO_3', //  => '',
            'FSOL_IMPUESTO_DIRECTO_TIPO_4', //  => '',

                    ],

                3 => [
/*
            'FSOL_TCACFG', //  => '',  // Tarifa
            'FSOL_AUSCFG', //  => '',  // Almacén
            'FSOL_SPCCFG', //  => '',  // Serie de Pedidos

            'FSOL_PIV1CFG', //  => '',
            'FSOL_PIV2CFG', //  => '',
            'FSOL_PIV3CFG', //  => '',

            'FSOL_PRE1CFG', //  => '',
            'FSOL_PRE2CFG', //  => '',
            'FSOL_PRE2CFG', //  => '',
*/
                    ],

                4 => [


                    ],

                5 => [

            'FSOL_TCACFG', //  => '',  // Tarifa
            'FSOL_AUSCFG', //  => '',  // Almacén
            'FSOL_SPCCFG', //  => '',  // Serie de Pedidos

            'FSOL_PIV1CFG', //  => '',
            'FSOL_PIV2CFG', //  => '',
            'FSOL_PIV3CFG', //  => '',

            'FSOL_PRE1CFG', //  => '',
            'FSOL_PRE2CFG', //  => '',
            'FSOL_PRE2CFG', //  => '',


                    ],

                6 => [


                    ],

                7 => [

//    'FSOL_LOAD_SECCIONES'           => '1',
//    'FSOL_LOAD_FAMILIAS'            => '1',
    'FSX_LOAD_FAMILIAS_TO_ROOT',
    'FSX_LOAD_ARTICULOS',
//    'FSX_ADD_TO_ALL_CATS'           => '0',  // Add Product to Section and Family
//    'FSOL_ARTICULOS_DESC_WEB'       => '2',  // 1=> la Descripción Corta, 2=> la Descripción Larga, 3=> Ambas
    'FSX_LOAD_ARTICULOS_ACTIVE',
//    'FSOL_LOAD_ARTICULOS_STATUS'     => 'draft',   // publish, pending, draft
 //   'FSOL_LOAD_ARTICULOS_VISIBILITY' => 'visible', // visible, catalog, search, hidden
    'FSX_LOAD_ARTICULOS_PRIZE_ALL',
    'FSX_LOAD_ARTICULOS_STOCK_ALL',
//    'FSOL_ARTICULOS_CAT_NOT_FOUND'  => '0',
    'FSX_PROD_ABI_ONLY_DEACTIVATE',
//    'FSX_RESUME_ON_TIMEOUT'         => '0',

            'FSX_FSOL_AUSCFG_PEER', //  => '',  // Almacén correspondiente en aBillander para cargar el stock
            
                    ],

                8 => [

            'FSOLWEB_SQL_LAST_DBUPDATE', //  => '',
            'FSX_CATALOGUE_LAST_RUN_DATE', //  => '',

                    ],

        ];

    /** @var array Connections.fsx-bbdd cache */
    protected static $_FSXCON;

    public static  $gates = NULL;


	public static function setFSxConnection()
	{
		self::$_FSXCON = [];


        // Get Configurations from FactuSOL Web
        if (config('app.url') =='http://abimfg.laextranatural.es' 
         || config('app.url') =='http://abimfg-test.laextranatural.es')
        {
                self::$_FSXCON['fsx-bbdd'] = 
                [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST', 'localhost'),
                    'port' => env('DB_PORT', '3306'),
                    'database' => env('DB_DATABASE_FSX', 'laextran_com'),
                    'username' => env('DB_USERNAME_FSX', 'laextran_com'),
                    'password' => env('DB_PASSWORD_FSX', 'DAS#6XqwyK%z'),
                    'unix_socket' => env('DB_SOCKET', ''),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
        //            'strict' => true,
                    'strict' => false,
                    'engine' => null,
                ];
        } else {
                self::$_FSXCON['fsx-bbdd'] = 
                [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '3306'),
                    'database' => env('DB_DATABASE_FSX', 'wooc_btester'),
                    'username' => env('DB_USERNAME_FSX', 'root'),
                    'password' => env('DB_PASSWORD_FSX', '1qaz2wsx'),
                    'unix_socket' => env('DB_SOCKET', ''),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
        //            'strict' => true,
                    'strict' => false,
                    'engine' => null,
                ];
        }

		\Config::set("database.connections.fsx-bbdd", self::$_FSXCON['fsx-bbdd'] );
	}

	/**
	  * Get a single configuration value
	  */
	public static function getFormasDePagoList()
	{
		// Force use cache
        if ( 0 )
        {
            // Payment Methods Cache
            $cache = Configuration::get('FSX_FORMAS_DE_PAGO_CACHE');

            $fpas = json_decode( $cache , true);
            ksort($fpas);

            return $fpas;
        }
        // See comments by the end of this file


        if (!self::$_FSXCON)
		{
			FSxTools::setFSxConnection();
		}


        // Start Logic Probe, now!
        try {

            // 'TABLA_FORMAS_PAGO'
            $formasp = \DB::connection('fsx-bbdd')->select('select `CODFPA` as id, `DESFPA` as description from `F_FPA` order by `DESFPA`');
        }

        catch( \Exception $e ) {

            return redirect()->route('fsxconfigurationkeys.index')
                    ->with('error', $e->getMessage());

        }
		
		return collect($formasp)->pluck('description', 'id')->toArray();
		
		// return collect($formasp)->map(function($x){ return (array) $x; })->toArray();
	}
    
    public static function getCodigoFormaDePago( $paymentm_id = '' )
    {
        if (!$paymentm_id) return null;

        // Dictionary
        if ( !isset(self::$gates) )
            self::$gates = json_decode(\App\Configuration::get('FSX_FORMAS_DE_PAGO_DICTIONARY_CACHE'), true);

        $gates = self::$gates;

        return isset($gates[$paymentm_id]) ? $gates[$paymentm_id] : null;
    }


/* ********************************************************************************************* */

	/**
	  * Get a single configuration value
	  */
	public static function getTipoIVA( $tax_id = null ) 
	{

		switch ($tax_id){
	            case Configuration::get('FSOL_IMPUESTO_DIRECTO_TIPO_1'):
	                  return 0;
	                  break;
	            case Configuration::get('FSOL_IMPUESTO_DIRECTO_TIPO_2'):
	                  return 1;
	                  break;
	            case Configuration::get('FSOL_IMPUESTO_DIRECTO_TIPO_3'):
	                  return 2;
	                  break; 
	            case Configuration::get('FSOL_IMPUESTO_DIRECTO_TIPO_4'):
	                  return 3;         // Exento
	                  break; 
	            default:
	                  return -1;
	                  break; 
		}   

	}


    static public function translate_tivart($fsol_tax_value) 
    {
        // Prototipo: function tiposiva($tipo){ (func.php)
        // $fsol_tax_value es TIVART
        switch (intval($fsol_tax_value)){
                case 0:
                      return Configuration::getInt('FSOL_IMPUESTO_DIRECTO_TIPO_1');
                      break;
                case 1:
                      return Configuration::getInt('FSOL_IMPUESTO_DIRECTO_TIPO_2');
                      break;
                case 2:
                      return Configuration::getInt('FSOL_IMPUESTO_DIRECTO_TIPO_3');
                      break; 
                case 3:
                      return Configuration::getInt('FSOL_IMPUESTO_DIRECTO_TIPO_4');
                      break; 
                default:
                      return -1;
                      break; 
        }      
    }


/* ********************************************************************************************* */



    public static function getPaymentMethodKey( $id = '' )
    {
            return 'FSX_PAYMENT_METHOD_'.strtoupper($id);
    }


}




/* ********************************************************** * /


Route::get('fpago', function()
{
    // aBillander Methods
    $pgatesList = \App\PaymentMethod::select('id', 'name')->orderby('name', 'desc')->get()->toArray();

    $l= [];

    foreach($pgatesList as $k => $v)
    {
        $l[] = 
                [
                    'id' => '00'.$v['id'],
                    'name' => $v['name']
                ];
    }

    $ll =collect($l)->pluck('name', 'id')->toArray();

    \App\Configuration::updateValue('FSX_FORMAS_DE_PAGO_CACHE', json_encode($ll));



    abi_r(  \App\Configuration::get('FSX_FORMAS_DE_PAGO_CACHE') );

    $fsolpaymethods = \Queridiam\FSxConnector\FSxTools::getFormasDePagoList();
    abi_r( ($fsolpaymethods ) );

});


/ * ********************************************************** */
