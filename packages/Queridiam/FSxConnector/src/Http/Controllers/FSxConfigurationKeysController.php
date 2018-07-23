<?php 

namespace Queridiam\FSxConnector\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

// use \aBillander\WooConnect\WooConnector;
// use \aBillander\WooConnect\WooOrderImporter;

use \App\Configuration as Configuration;

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
		return view('fsx_connector::fsx_configuration_keys.index');
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

}


/* ********************************************************************************************* */   

