<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Customer;
use App\Address;
use App\PriceRule;

class AbccCustomerController extends Controller
{


   protected $customer;
   protected $address;

   public function __construct(Customer $customer, Address $address)
   {
        // $this->middleware('auth:customer');

        $this->customer = $customer;
        $this->address  = $address;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 

        // return view('abcc.customers.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        // Get logged in user
        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;

        $tab_index = 'customer';
        
        return view('abcc.account.edit', compact('customer_user', 'customer', 'tab_index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Get logged in user
        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;
        $address = $customer->address;

//        $rules = array_except( Customer::$rules, array('password') );
        $rules = [];
        $this->validate($request, $rules);

        $request->merge( ['name_commercial' => $request->input('address.name_commercial')] );

        // $customer->update( array_merge($request->all(), ['name_commercial' => $request->input('address.name_commercial')] ) );
        $customer->update( $request->all() );
        if ( !$request->input('address.name_commercial') ) $request->merge( ['address.name_commercial' => $request->input('name_fiscal')] );
        $data = $request->input('address');
        $address->update($data);


       return redirect()->route('abcc.customer.edit')
            ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') . $request->input('name_commercial'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getQuantityPriceRules(Request $request)
    {
        $items_per_page_pricerules = intval($request->input('items_per_page_pricerules', \App\Configuration::get('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page_pricerules >= 0) ) 
            $items_per_page_pricerules = \App\Configuration::get('DEF_ITEMS_PERPAGE');

        $customer      = Auth::user()->customer;
        $id = $customer->id;

        $customer_rules = PriceRule::where('currency_id', $customer->currency->id)
                    // Customer range
                    ->where( function($query) use ($customer) {
                                $query->where('customer_id', $customer->id);
                                $query->orWhere( function($query1) {
                                        $query1->whereDoesntHave('customer');
                                    } );
                                if ($customer->customer_group_id)
                                    $query->orWhere('customer_group_id', $customer->customer_group_id);
                        } )
                    // Product range
                    ->with('product')
                    ->whereHas('product', function ($query) use ($customer) {
                            $query
                                      ->IsSaleable()
                                      ->IsAvailable()
                                      ->qualifyForCustomer( $customer->id, $customer->currency->id)
                                      ->IsActive()
                                      ->IsPublished();
                        })
                    // All Products
//                    ->where( function($query) use ($product) {
//                                $query->where('product_id', $product->id);
//                                if ($product->category_id)
//                                    $query->orWhere('category_id',  $product->category_id);
//                        } )
                    // Quantity range
                    ->where( 'from_quantity', '>=', 1 )
                    // Date range
                    ->where( function($query){
                                $now = \Carbon\Carbon::now()->startOfDay(); 
                                $query->where( function($query) use ($now) {
                                    $query->where('date_from', null);
                                    $query->orWhere('date_from', '<=', $now);
                                } );
                                $query->where( function($query) use ($now) {
                                    $query->where('date_to', null);
                                    $query->orWhere('date_to', '>=', $now);
                                } );
                        } )
                                ->orderBy('product_id', 'ASC')
                                ->orderBy('from_quantity', 'ASC');

        $customer_rules = $customer_rules->paginate( $items_per_page_pricerules );     // \App\Configuration::get('DEF_ITEMS_PERPAGE') );  // intval(\App\Configuration::get('DEF_ITEMS_PERAJAX'))

        $customer_rules->setPath('customerpricerules');
/*
        $customer_rules1 = $customer_rules->map(function ($item, $key) use ($customer) {
                            if ($item->product_id > 0)
                            {
                                //
                                $item->customer_price = $item->product->getPriceByCustomerPriceList( $customer, 1, $customer->currency );
                            }

                            return $item;
                        });
*/        
        return view('abcc.customer.pricerules_list', compact('id', 'customer_rules', 'items_per_page_pricerules', 'customer'));
    }
}
