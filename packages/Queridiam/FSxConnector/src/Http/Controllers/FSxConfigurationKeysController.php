<?php 

namespace Queridiam\FSxConnector\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

// use \aBillander\WooConnect\WooConnector;
// use \aBillander\WooConnect\WooOrderImporter;

use \App\Configuration;

class FSxConfigurationKeysController extends Controller {

   public $conf_keys = [];

   public function __construct()
   {

        $this->conf_keys = [

                1 => [

                        'FSX_IMPERSONATE_TIMEOUT',
						'FSX_TIME_OFFSET'               => '3',
						'FSX_MAX_ROUNDCYCLES'           => '50',

                        'FSOL_WEB_CUSTOMER_CODE_BASE'     => 50000,
						'FSOL_WEB_GUEST_CODE_BASE'        => 60000,		// 'WOOC_ENABLE_GUEST_CHECKOUT'
                        'FSOL_ABI_CUSTOMER_CODE_BASE'     => 80000,


						'FSOL_CBDCFG' => '/public_html/laextranatural.com/wp-content/plugins/FSx-Connector/fsweb/BBDD/',
						'FSOL_CIACFG' => 'imagenes/',
						'FSOL_CPVCFG' => 'npedidos/',
						'FSOL_CCLCFG' => 'nclientes/',
						'FSOL_CBRCFG' => 'factusolweb.sql',

						'FSOL_TCACFG' => '',	// Tarifa
						'FSOL_AUSCFG' => '',	// Almacén
						'FSOL_SPCCFG' => '',	// Serie de Pedidos

						'FSOL_PIV1CFG' => '',
						'FSOL_PIV2CFG' => '',
						'FSOL_PIV3CFG' => '',

						'FSOL_PRE1CFG' => '',
						'FSOL_PRE2CFG' => '',
						'FSOL_PRE2CFG' => '',

						'FSOL_IMPUESTO_DIRECTO_TIPO_1' => '-1',
						'FSOL_IMPUESTO_DIRECTO_TIPO_2' => '-1',
						'FSOL_IMPUESTO_DIRECTO_TIPO_3' => '-1',
						'FSOL_IMPUESTO_DIRECTO_TIPO_4' => '-1',

						'',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',

                    ],

                2 => [


                    ],

                3 => [


                    ],

                4 => [


                    ],

                5 => [


                    ],
        ];

   }

	/**
	 * Display a listing of the resource.
	 * GET /something
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        // return view('fsx_connector::fsx_configuration_keys.index');

        $conf_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;
        
        return redirect()->route('fsx.configuration');

        // Check tab_index
        $tab_view = 'fsx_connector::fsx_configuration_keys.'.'key_group_'.intval($tab_index);
        if (!\View::exists($tab_view)) 
            return \Redirect::to('404');

        $key_group = [];

        foreach ($this->conf_keys[$tab_index] as $key)
            $key_group[$key]= Configuration::get($key);

        $currencyList = \App\Currency::pluck('name', 'id')->toArray();
        $customer_groupList = \App\CustomerGroup::pluck('name', 'id')->toArray();
        $price_listList = \App\PriceList::pluck('name', 'id')->toArray();
        $languageList = \App\Language::pluck('name', 'id')->toArray();
        $orders_sequenceList = \App\Sequence::listFor( \App\CustomerOrder::class );
        $taxList = \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view( $tab_view, compact('tab_index', 'key_group', 'currencyList', 'customer_groupList', 'price_listList', 'languageList', 'orders_sequenceList', 'taxList') );

	}
   
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function rindex(Request $request)
    {
        $conf_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;
        
        // Check tab_index
        $tab_view = 'woo_connect::woo_configuration_keys.'.'key_group_'.intval($tab_index);
        if (!\View::exists($tab_view)) 
            return \Redirect::to('404');

        $key_group = [];

        foreach ($this->conf_keys[$tab_index] as $key)
            $key_group[$key]= Configuration::get($key);

        $currencyList = \App\Currency::pluck('name', 'id')->toArray();
        $customer_groupList = \App\CustomerGroup::pluck('name', 'id')->toArray();
        $price_listList = \App\PriceList::pluck('name', 'id')->toArray();
        $languageList = \App\Language::pluck('name', 'id')->toArray();
        $orders_sequenceList = \App\Sequence::listFor( \App\CustomerOrder::class );
        $taxList = \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view( $tab_view, compact('tab_index', 'key_group', 'currencyList', 'customer_groupList', 'price_listList', 'languageList', 'orders_sequenceList', 'taxList') );

        // https://bootsnipp.com/snippets/M27e3
    }
	
	

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // die($request->input('tab_index'));

        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : -1;
        
        $key_group = isset( $this->conf_keys[$tab_index] ) ?
                            $this->conf_keys[$tab_index]   :
                            null ;

        // Check tab_index
        if (!$key_group) 
            return redirect('404');

        foreach ($key_group as $key) 
        {
            if ($request->has($key)) {

                // abi_r("-$key-");
                // abi_r($request->input($key));

                // Prevent NULL values
                $value = is_null( $request->input($key) ) ? '' : $request->input($key);

                \App\Configuration::updateValue($key, $value);
            }
        }

        // die();

        return redirect('wooc/wooconnect/wooconfigurationkeys?tab_index='.$tab_index)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $tab_index], 'layouts') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // 
    }


/* ********************************************************************************************* */    


    /**
     * Show the form for editing the specified resource.
     * GET 
     *
     * @param  
     * @return Response
     */
    public function configurationsEdit()
    {
        $tab_index = 5;

        $fsxconfs = [];

        $fsxconfs[] = [ 'id' => 'FSOL_TCACFG', 'value' => Configuration::get('FSOL_TCACFG') ];
        $fsxconfs[] = [ 'id' => 'FSOL_AUSCFG', 'value' => Configuration::get('FSOL_AUSCFG') ];
        $fsxconfs[] = [ 'id' => 'FSOL_SPCCFG', 'value' => Configuration::get('FSOL_SPCCFG') ];
        
        $fsxconfs[] = [ 'id' => 'FSOL_PIV1CFG', 'value' => Configuration::get('FSOL_PIV1CFG') ];
        $fsxconfs[] = [ 'id' => 'FSOL_PIV2CFG', 'value' => Configuration::get('FSOL_PIV2CFG') ];
        $fsxconfs[] = [ 'id' => 'FSOL_PIV3CFG', 'value' => Configuration::get('FSOL_PIV3CFG') ];
        
        $fsxconfs[] = [ 'id' => 'FSOL_PRE1CFG', 'value' => Configuration::get('FSOL_PRE1CFG') ];
        $fsxconfs[] = [ 'id' => 'FSOL_PRE2CFG', 'value' => Configuration::get('FSOL_PRE2CFG') ];
        $fsxconfs[] = [ 'id' => 'FSOL_PRE3CFG', 'value' => Configuration::get('FSOL_PRE3CFG') ];


        return view('fsx_connector::fsx_configuration_keys.'.'key_group_'.$tab_index, compact('tab_index', 'fsxconfs'));
    }

    /**
     * Update the specified resource in storage.
     * PUT 
     *
     * @param  
     * @return Response
     */
    public function configurationsUpdate(Request $request)
    {
        $settings = [];

        // Get Configurations from FactuSOL Web
        if (config('app.url') =='http://abimfg.laextranatural.es')
        {
                \Config::set("database.connections.fsx-bbdd", [
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
                ]);
        } else {
                \Config::set("database.connections.fsx-bbdd", [
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
                ]);
        }

        // Start Logic Probe, now!
        try {

            // $groups = \DB::connection('fsx-bbdd')->select( "select * from ".TABLA_CONFIGURACION." WHERE `CODCFG` = ?", [1] );

            $settings = \DB::connection('fsx-bbdd')->table( TABLA_CONFIGURACION )->where('CODCFG', '1')->first();
        }

        catch( \Exception $e ) {

            /*
            $e->getMessage(); // Error message.

            $e->getRequest(); // Last request data.

            $e->getResponse(); // Last response data.
            */

            return redirect()->route('fsxconfigurationkeys.index')
                    ->with('error', $e->getMessage());

        }

        // Save Settings Cache

        Configuration::updateValue('FSOL_CONFIGURATIONS_CACHE', json_encode($settings));

        // Set some handy values

        Configuration::updateValue('FSOL_TCACFG', $settings->TCACFG);
        Configuration::updateValue('FSOL_AUSCFG', $settings->AUSCFG);
        Configuration::updateValue('FSOL_SPCCFG', $settings->SPCCFG);

        Configuration::updateValue('FSOL_PIV1CFG', $settings->PIV1CFG);
        Configuration::updateValue('FSOL_PIV2CFG', $settings->PIV2CFG);
        Configuration::updateValue('FSOL_PIV3CFG', $settings->PIV3CFG);

        Configuration::updateValue('FSOL_PRE1CFG', $settings->PRE1CFG);
        Configuration::updateValue('FSOL_PRE2CFG', $settings->PRE2CFG);
        Configuration::updateValue('FSOL_PRE3CFG', $settings->PRE3CFG);


        return redirect('fsx/fsxconfiguration')
                ->with('success', l('This configuration has been successfully updated', [], 'layouts'));
    }


/* ********************************************************************************************* */


    /**
     * Show the form for editing the specified resource.
     * GET 
     *
     * @param  
     * @return Response
     */
    public function configurationPaymentMethodsEdit()
    {
        $tab_index = 3;

        return $tab_index ;

        $woopgates = WooConnector::getWooPaymentGateways();

// abi_r($woopgates, true);

        // Save Payment Gateways Cache
        // Loose some fat first
        $woopgates = array_map(function($value){
            unset($value['settings']);
            return $value;
        }, $woopgates);
        Configuration::updateValue('WOOC_PAYMENT_GATEWAYS_CACHE', json_encode($woopgates));
        // Woo Payment Gateways Dictionary
        $dic = [];
        $dic_val = [];
        foreach ($woopgates as $woopgate) {
            $dic[$woopgate['id']] = WooConnector::getPaymentGatewayKey( $woopgate['id'] );
            $dic_val[$woopgate['id']] = Configuration::get($dic[$woopgate['id']]);
        }

        $pgatesList = \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('woo_connect::woo_configuration_keys.'.'key_group_'.$tab_index, compact('tab_index', 'woopgates', 'dic', 'dic_val', 'pgatesList'));
    }

    /**
     * Update the specified resource in storage.
     * PUT 
     *
     * @param  
     * @return Response
     */
    public function configurationPaymentMethodsUpdate(Request $request)
    {
        // Validation rules
        $rules = [];
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            $rules[ 'dic.'.$key ] = 'sometimes|nullable|exists:payment_methods,id';
        }

        $this->validate($request, $rules);
        
        foreach ( $request->input('dic') as $key => $val) 
        {
            Configuration::updateValue($key, $val);
        }
        // Save Payment Gateways Dictionary Cache
        $dic_val = [];
        $woopgates = json_decode(Configuration::get('WOOC_PAYMENT_GATEWAYS_CACHE'), true);
        foreach ($woopgates as $woopgate) {
            $dic_val[$woopgate['id']] = Configuration::get(WooConnector::getPaymentGatewayKey($woopgate['id']));
        }

        Configuration::updateValue('WOOC_PAYMENT_GATEWAYS_DICTIONARY_CACHE', json_encode($dic_val));

//      abi_r($request->input('dic'));
//      abi_r($dic_val, true);

        return redirect()->route('wooconnect.configuration.paymentgateways')
                ->with('success', l('This configuration has been successfully updated', [], 'layouts'));
    }


/* ********************************************************************************************* */


}


/* ********************************************************************************************* */   





// Define the database table names used in the project
  define('TABLA_AGENTES'        , 'F_AGE');
  define('TABLA_ALMACENES'      , 'F_ALM');
  define('TABLA_ARTICULOS'      , 'F_ART');
  define('TABLA_AUT'            , 'F_AUT');
  define('TABLA_CONFIGURACION'  , 'F_CFG');
  define('TABLA_CLIENTES'       , 'F_CLI');
  define('TABLA_DESCUENTOS'     , 'F_DES');
  define('TABLA_DIRECCIONES'    , 'F_DIR');
  define('TABLA_EMPRESAS'       , 'F_EMP');
  define('TABLA_FACTURAS'       , 'F_FAC');
  define('TABLA_FAMILIAS'       , 'F_FAM');
  define('TABLA_FORMAS_PAGO'    , 'F_FPA');
  define('TABLA_LINEAS_FACTURAS', 'F_LFA');
  define('TABLA_LINEAS_PEDIDOS' , 'F_LPC');
  define('TABLA_LINEAS_TARIFAS' , 'F_LTA');
  define('TABLA_PEDIDOS'        , 'F_PCL');
  define('TABLA_SECCIONES'      , 'F_SEC');
  define('TABLA_STOCK'          , 'F_STO');
  define('TABLA_TARIFAS'        , 'F_TAR');
  
  define('TABLA_DICCIONARIO'    , 'fsx_dic');
  define('TABLE_FSX_LOG'        , 'fsx_log');
  define('TABLE_FSX_ORDERS'     , 'fsx_orders');
