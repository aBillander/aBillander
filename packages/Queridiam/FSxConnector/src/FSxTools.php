<?php 

namespace Queridiam\FSxConnector;

use \App\Configuration;

class FSxTools
{

	/** @var array Connections.fsx-bbdd cache */
	protected static $_FSXCON;


	public static function setFSxConnection()
	{
		self::$_FSXCON = [];


        // Get Configurations from FactuSOL Web
        if (config('app.url') =='http://abimfg.laextranatural.es')
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
	public static function getFormasDePago()
	{
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
		
		return collect($formasp)->map(function($x){ return (array) $x; })->toArray();
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



    public static function getFormaDePagoKey( $id = '' )
    {
            return 'FSX_PAYMENT_METHOD_'.strtoupper($id);
    }


}