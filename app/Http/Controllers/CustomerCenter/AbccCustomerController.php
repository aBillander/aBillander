<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Customer;
use App\Address;

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
}
