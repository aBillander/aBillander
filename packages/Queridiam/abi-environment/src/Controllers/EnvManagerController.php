<?php

namespace Queridiam\EnvManager\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class EnvManagerController extends Controller
{

   public $env_keys = [];

   public function __construct()
   {

        $this->env_keys = [

                // SMTP mail keys
                1 => [

                        'WOOC_STORE_URL',
                    ],

                // WooCommerce shop keys
                2 => [

                        'WOOC_STORE_URL',
                    ],
        ];

   }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        abi_r('xxx'); die();

        $env_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;
        
        // Check tab_index
        $tab_view = 'envmanager::env_keys.'.'key_group_'.intval($tab_index);
        if (!\View::exists($tab_view)) 
            return \Redirect::to('404');

        $key_group = [];

        foreach ($this->env_keys[$tab_index] as $key)
            $key_group[$key]= Configuration::get($key);

        $currencyList = Currency::pluck('name', 'id')->toArray();
        $customer_groupList = CustomerGroup::pluck('name', 'id')->toArray();
        $price_listList = PriceList::pluck('name', 'id')->toArray();
        $warehouseList = Warehouse::pluck('name', 'id')->toArray();
        if(count($warehouseList) != 1)
            $warehouseList = ['0' => l('-- All --', [], 'layouts')] + $warehouseList;

        $languageList = Language::pluck('name', 'id')->toArray();
        $orders_sequenceList = Sequence::listFor( CustomerOrder::class );
        $taxList = Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray();
        $woo_product_statusList = [];
        foreach (WooProduct::$statuses as $value) {
            // code...
            $woo_product_statusList[$value] = $value;
        }

        return view( $tab_view, compact('tab_index', 'key_group', 'currencyList', 'customer_groupList', 'price_listList', 'warehouseList', 'languageList', 'orders_sequenceList', 'taxList', 'woo_product_statusList') );
    }
}