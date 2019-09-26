<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Configuration;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $this->address = $address;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        // Get logged in user
        $customer_user = Auth::user();
        $customer = Auth::user()->customer;

        $tab_index = 'customer';

        return view('abcc.account.edit', compact('customer_user', 'customer', 'tab_index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param Customer $customer
     * @return Response
     */
    public function update(Request $request)
    {
        // Get logged in user
        $customer_user = Auth::user();
        $customer = Auth::user()->customer;
        $address = $customer->address;

        $rules = [];
        $this->validate($request, $rules);

        $request->merge(['name_commercial' => $request->input('address.name_commercial')]);

        // $customer->update( array_merge($request->all(), ['name_commercial' => $request->input('address.name_commercial')] ) );
        $customer->update($request->all());
        if (!$request->input('address.name_commercial')) {
            $request->merge(['address.name_commercial' => $request->input('name_fiscal')]);
        }
        $data = $request->input('address');
        $address->update($data);


        return redirect()->route('abcc.customer.edit')
                         ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') . $request->input('name_commercial'));
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function getQuantityPriceRules(Request $request)
    {
        $items_per_page_pricerules = intval($request->input('items_per_page_pricerules', Configuration::get('DEF_ITEMS_PERPAGE')));
        if (!($items_per_page_pricerules >= 0)) {
            $items_per_page_pricerules = Configuration::get('DEF_ITEMS_PERPAGE');
        }

        $customer = Auth::user()->customer;
        $id = $customer->id;

        $customer_rules = PriceRule::where('currency_id', $customer->currency->id) // Customer range

                                   ->where(function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
            if ($customer->customer_group_id) {
                $query->orWhere('customer_group_id', $customer->customer_group_id);
            }
        })
                                   ->with('product') // Product range
                                   ->whereHas('product', function ($query) use ($customer) {
                $query
                    ->IsSaleable()
                    ->IsAvailable()
                    ->qualifyForCustomer($customer->id, $customer->currency->id)
                    ->IsActive();
            })
            // All Products
            //                    ->where( function($query) use ($product) {
            //                                $query->where('product_id', $product->id);
            //                                if ($product->category_id)
            //                                    $query->orWhere('category_id',  $product->category_id);
            //                        } )
            // Quantity range
                                   ->where('from_quantity', '>', 1)
            // Date range
                                   ->where(function ($query) {
                $now = Carbon::now()->startOfDay();
                $query->where(function ($query) use ($now) {
                    $query->where('date_from', null);
                    $query->orWhere('date_from', '<=', $now);
                });
                $query->where(function ($query) use ($now) {
                    $query->where('date_to', null);
                    $query->orWhere('date_to', '>=', $now);
                });
            })
                                   ->orderBy('product_id', 'ASC')
                                   ->orderBy('from_quantity', 'ASC');

        $customer_rules = $customer_rules->paginate($items_per_page_pricerules);     // \App\Configuration::get('DEF_ITEMS_PERPAGE') );  // intval(\App\Configuration::get('DEF_ITEMS_PERAJAX'))

        $customer_rules->setPath('customerpricerules');

        return view('abcc.customer.pricerules_list', compact('id', 'customer_rules', 'items_per_page_pricerules'));
    }
}
