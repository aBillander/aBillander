<?php 

namespace aBillander\WooConnect;

use \App\Configuration;

class FSxTools
{

	/** @var array Connections.fsx-bbdd cache */
	protected static $_FSXCON;

    public static  $gates = NULL;


	public static function setFSxConnection()
	{
		self::$_FSXCON = [];


        // Get Configurations from FactuSOL Web
        if (config('app.url') =='http://abimfg.laextranatural.es' 
         || config('app.url') =='http://abimfg-test.laextranatural.es'
         || 0 )
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
//                    'database' => env('DB_DATABASE_FSX', 'wooc_btester'),
                    'database' => env('DB_DATABASE_FSX', 'xtranat55'),
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


/* ********************************************************************************************* */


    static public function translate_customers_fsol($woo_value) {
        if (!$woo_value) return false;

        if (!self::$_FSXCON)
        {
            FSxTools::setFSxConnection();
        }

        // Data ordering in records is reversed!
        return  self::translate_woo('CUSTOMERS', $woo_value);
    }

    static public function translate_woo($fsol_group_key, $fsol_value) {

        $table_name = 'wp_fsx_dic';

        // $table_name = $wpdb->prefix . TABLA_DICCIONARIO;
        $customer_external_reference = \DB::connection('fsx-bbdd')
                            ->select("select woo_value from $table_name
                             where fsol_group_key='$fsol_group_key'
                             and fsol_value='$fsol_value'");

        return $customer_external_reference ? $customer_external_reference[0]->woo_value : null;
    }

    static public function new_customers_entry($woo_value, $fsol_value) { 

        // sxMapper::new_entry('CUSTOMERS', $$this->customer['wooID'], $this->customer['csID']);
        return self::new_entry('CUSTOMERS', $woo_value, $fsol_value);
    }

    static public function new_entry($fsol_group_key, $fsol_value, $woo_value) { 

        $table_name = 'wp_fsx_dic';

        // $table_name = $wpdb->prefix . TABLA_DICCIONARIO;
        $sql_data_array = array('fsol_group_key' => $fsol_group_key,
                                'fsol_value' => $fsol_value,
                                'woo_value' => $woo_value,
                                'last_modified' => date("Y-m-d h:i:s"),
                                'date_added' => date("Y-m-d h:i:s") );

        $result=\DB::table($table_name)->insert( $sql_data_array );

        return $result;
    }


/* ********************************************************************************************* */


	/**
	  * Get a single configuration value
	  */
	public static function getFormasDePagoList()
	{
		// Force use cache
        if ( 1 )
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
