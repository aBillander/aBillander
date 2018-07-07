<?php 

namespace aBillander\WooConnect\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

use \aBillander\WooConnect\WooConnector;
// use \aBillander\WooConnect\WooOrderImporter;

use \App\Configuration as Configuration;


class WooConfigurationKeysController extends Controller {

   public $conf_keys = array();

   public function __construct()
   {

        $this->conf_keys = [

                1 => [

                        'WOOC_DECIMAL_PLACES',
                        'WOOC_DEF_CURRENCY',
                        'WOOC_DEF_CUSTOMER_GROUP',
                        'WOOC_DEF_CUSTOMER_PRICE_LIST',

                        'WOOC_DEF_LANGUAGE',
                        'WOOC_DEF_ORDERS_SEQUENCE',
                        'WOOC_DEF_SHIPPING_TAX',

                        'WOOC_ORDER_NIF_META',
                        'WOOC_ORDERS_PER_PAGE',

                        'WOOC_USE_LOCAL_PRODUCT_NAME',

                    ],

                2 => [

                        'DEF_CARRIER',
                        'DEF_COMPANY',
                        'DEF_COUNTRY',
                        'DEF_CURRENCY',
                        'DEF_CUSTOMER_INVOICE_SEQUENCE',
                        'DEF_CUSTOMER_INVOICE_TEMPLATE',
                        'DEF_CUSTOMER_PAYMENT_METHOD',
                        'DEF_LANGUAGE',
                        'DEF_MEASURE_UNIT_FOR_BOMS',
                        'DEF_MEASURE_UNIT_FOR_PRODUCTS',
                        'DEF_OUTSTANDING_AMOUNT',
                        'DEF_TAX',
                        'DEF_WAREHOUSE',

                    ],

                3 => [

                        'DEF_ITEMS_PERAJAX',
                        'DEF_ITEMS_PERPAGE',
                        'DEF_PERCENT_DECIMALS',
                        'DEF_QUANTITY_DECIMALS',
//                        'DEF_DIMENSION_UNIT',
//                        'DEF_DISTANCE_UNIT',
//                        'DEF_VOLUME_UNIT',
//                        'DEF_WEIGHT_UNIT',
//                        'HEADER_TITLE',
//                        'SUPPORT_CENTER_EMAIL',
//                        'SUPPORT_CENTER_NAME',
                        'TIMEZONE',
                        'USE_CUSTOM_THEME',

                    ],

                4 => [

                        'SKU_PREFIX_LENGTH',
                        'SKU_PREFIX_OFFSET',
                        'SKU_SEPARATOR',
                        'SKU_SUFFIX_LENGTH',

                    ],
        ];

   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
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

}