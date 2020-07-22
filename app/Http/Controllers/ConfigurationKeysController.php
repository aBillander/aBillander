<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \App\Configuration as Configuration;
use View;

class ConfigurationKeysController extends Controller {

   public $conf_keys = array();

   public function __construct()
   {

        $this->conf_keys = [

                1 => [

                        'ALLOW_PRODUCT_SUBCATEGORIES',
                        'ALLOW_SALES_RISK_EXCEEDED',
                        'ALLOW_SALES_WITHOUT_STOCK',
                        'CUSTOMER_ORDERS_NEED_VALIDATION',
                        'ALLOW_CUSTOMER_BACKORDERS',
                        'ENABLE_COMBINATIONS',
                        'ENABLE_ECOTAXES',
                        'PRICES_ENTERED_WITH_ECOTAX',
                        'ENABLE_CUSTOMER_CENTER',
                        'ENABLE_SALESREP_CENTER',
                        'ENABLE_MANUFACTURING',
                        'MRP_WITH_STOCK',
                        'MRP_WITH_ZERO_ORDERS',
                        'ENABLE_LOTS',
                        'PRINT_LOT_NUMBER_ON_DOCUMENTS',
                        'ENABLE_WEBSHOP_CONNECTOR',
                        'ENABLE_FSOL_CONNECTOR',
                        'SELL_ONLY_MANUFACTURED',
                        'MARGIN_METHOD',
                        'INCLUDE_SHIPPING_COST_IN_PROFIT',
                        'NEW_PRICE_LIST_POPULATE',
                        'NEW_PRODUCT_TO_ALL_PRICELISTS',
                        'PRICES_ENTERED_WITH_TAX',
                        'PRODUCT_NOT_IN_PRICELIST',
                        'QUOTES_EXPIRE_AFTER',
                        'ROUND_PRICES_WITH_TAX',
                        'DOCUMENT_ROUNDING_METHOD',
                        'SKU_AUTOGENERATE',
                        'TAX_BASED_ON_SHIPPING_ADDRESS',

                    ],

                2 => [

                        'DEF_COMPANY',
                        'DEF_COUNTRY',
                        'DEF_CURRENCY',
                        'DEF_CUSTOMER_QUOTATION_SEQUENCE',
                        'DEF_CUSTOMER_QUOTATION_TEMPLATE',
                        'DEF_CUSTOMER_ORDER_SEQUENCE',
                        'DEF_CUSTOMER_ORDER_TEMPLATE',
                        'DEF_CUSTOMER_SHIPPING_SLIP_SEQUENCE',
                        'DEF_CUSTOMER_SHIPPING_SLIP_TEMPLATE',
                        'DEF_CUSTOMER_INVOICE_SEQUENCE',
                        'DEF_CUSTOMER_INVOICE_TEMPLATE',
                        'CUSTOMER_INVOICE_BANNER',
                        'CUSTOMER_INVOICE_TAX_LABEL',
                        'CUSTOMER_INVOICE_CAPTION',
                        'DEF_CUSTOMER_PAYMENT_METHOD',
                        'DEF_SHIPPING_METHOD',
                        'DEF_LANGUAGE',
                        'DEF_CATEGORY',
                        'DEF_MEASURE_UNIT_FOR_BOMS',
                        'DEF_MEASURE_UNIT_FOR_PRODUCTS',
                        'DEF_OUTSTANDING_AMOUNT',
                        'DEF_TAX',
                        'DEF_WAREHOUSE',

                    ],

                3 => [

                        'DEF_ITEMS_PERAJAX',
                        'DEF_ITEMS_PERPAGE',
                        'DEF_LOGS_PERPAGE',
                        'DEF_PERCENT_DECIMALS',
                        'DEF_QUANTITY_DECIMALS',
                        'BUSINESS_NAME_TO_SHOW',
                        'ALLOW_IP_ADDRESSES', 
                        'MAX_DB_BACKUPS',
                        'MAX_DB_BACKUPS_ACTION',
                        'RECENT_SALES_CLASS',
                        'ABI_IMPERSONATE_TIMEOUT',
                        'ABI_TIMEOUT_OFFSET',
                        'ABI_MAX_ROUNDCYCLES',
                        'ENABLE_CRAZY_IVAN',
//                        'DEF_DIMENSION_UNIT',
//                        'DEF_DISTANCE_UNIT',
//                        'DEF_VOLUME_UNIT',
//                        'DEF_WEIGHT_UNIT',
//                        'HEADER_TITLE',
//                        'SUPPORT_CENTER_EMAIL',
//                        'SUPPORT_CENTER_NAME',
                        'TIMEZONE',
                        'USE_CUSTOM_THEME',
                        'DEVELOPER_MODE',

                    ],

                4 => [

                        'SKU_PREFIX_LENGTH',
                        'SKU_PREFIX_OFFSET',
                        'SKU_SEPARATOR',
                        'SKU_SUFFIX_LENGTH',

                    ],

                5 => [  // Customer Center

                        'ABCC_HEADER_TITLE',
                        'ABCC_EMAIL',
                        'ABCC_EMAIL_NAME',
                        'ABCC_DEFAULT_PASSWORD',
                        'ABCC_LOGIN_REDIRECT',
                        'ABCC_STOCK_SHOW',
                        'ABCC_STOCK_THRESHOLD',
                        'ABCC_OUT_OF_STOCK_PRODUCTS',
                        'ABCC_OUT_OF_STOCK_PRODUCTS_NOTIFY',
                        'ABCC_OUT_OF_STOCK_TEXT',
                        'ABCC_ORDERS_NEED_VALIDATION',
                        'ABCC_ENABLE_QUOTATIONS',
                        'ABCC_ENABLE_SHIPPING_SLIPS',
                        'ABCC_ENABLE_INVOICES',
                        'ABCC_ENABLE_MIN_ORDER',
                        'ABCC_MIN_ORDER_VALUE',
                        'ABCC_MAX_ORDER_VALUE',
                        'ABCC_DISPLAY_PRICES_TAX_INC',
                        'ABCC_ENABLE_NEW_PRODUCTS',
                        'ABCC_NBR_DAYS_NEW_PRODUCT',
                        'ABCC_NBR_ITEMS_IS_QUANTITY',
                        'ABCC_ITEMS_PERPAGE',
                        'ABCC_CART_PERSISTANCE',
                        'ABCC_DEFAULT_ORDER_TEMPLATE',
                        'ABCC_ORDERS_SEQUENCE',
                        'ABCC_QUOTATIONS_SEQUENCE',

                    ],

                51 => [  // Customer Center :: Shipping

                        'ABCC_SHIPPING_LABEL',
                        'ABCC_FREE_SHIPPING_PRICE',
                        'ABCC_STATE_42_SHIPPING',
                        'ABCC_COUNTRY_1_SHIPPING',
                        'ABCC_SHIPPING_TAX',

                    ],

                6 => [  // Sales Representatives Center

                        'ABSRC_HEADER_TITLE',
                        'ABSRC_EMAIL',
                        'ABSRC_EMAIL_NAME',
                        'ABSRC_DEFAULT_PASSWORD',

                        'ABSRC_ALLOW_ABCC_ACCESS',
                        'ABSRC_ITEMS_PERPAGE',

                    ],
        ];

   }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $conf_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;
        
        // Check tab_index
        $tab_view = 'configuration_keys.'.'key_group_'.intval($tab_index);
        if (!View::exists($tab_view)) 
            return \Redirect::to('404');

        $key_group = [];

        foreach ($this->conf_keys[$tab_index] as $key)
            $key_group[$key]= Configuration::get($key);

        // Temporarily
        if ($tab_index==5)
        foreach ($this->conf_keys[51] as $key)
            $key_group[$key]= Configuration::get($key);

        return view( $tab_view, compact('tab_index', 'key_group') );

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
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
         // abi_r($request->all());
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

                // abi_r($key);
                // abi_r($request->input($key));

                // Prevent NULL values
                $value = is_null( $request->input($key) ) ? '' : $request->input($key);

                \App\Configuration::updateValue($key, $value);
            }
        }

        // Temporarily
        if ($tab_index==51) $tab_index=5;

        return redirect('configurationkeys?tab_index='.$tab_index)
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