<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;

class CartsController extends Controller
{

   protected $cart;

   public function __construct(Cart $cart)
   {
        $this->cart = $cart;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = $this->cart
                            ->whereHas('cartlines')
                            ->with('customer')
                            ->with('currency')
//                            ->with('paymentmethod')
//                            ->orderBy('document_date', 'desc')
                            ->orderBy('updated_at', 'desc');        // ->get();

        $carts = $carts->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $carts->setPath('carts');
        
        return view('carts.index', compact('carts'));
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
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        $customer = $cart->customer;
        return view('carts.show', compact('cart', 'customer'));


        $this->customer_user = Auth::user();
        $this->customer      = Auth::user()->customer;

        $order = $customerorder;

        $customer = \App\Customer::find( $order->customer_id );

        $addressBook       = $customer->addresses;

        $theId = $customer->invoicing_address_id;
        $invoicing_address = $addressBook->filter(function($item) use ($theId) {    // Filter returns a collection!
            return $item->id == $theId;
        })->first();

        $addressbookList = array();
        foreach ($addressBook as $address) {
            $addressbookList[$address->id] = $address->alias;
        }

        // Dates (cuen)
        $this->addFormDates( ['document_date', 'delivery_date', 'export_date'], $order );

        return view('customer_orders.edit', compact('customer', 'invoicing_address', 'addressBook', 'addressbookList', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }



    public function updatePrices(Cart $cart)
    {
        // abi_r($cart->customer);die();


        // Update Cart Prices
        $cart->updateLinePricesByAdmin();
        
        return redirect()->route('carts.show', [$cart->id])
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $cart->id], 'layouts'));
    }
}
