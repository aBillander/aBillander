<?php

namespace App\Http\Controllers\CustomerCenter;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Customer;
use App\Address;

class AbccCustomerAddressesController extends Controller
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
        // Get logged in user
        $customer_user = Auth::user();
        $customer      = Auth::user()->customer;

        $aBook       = $customer->addresses;

        $tab_index = 'addresses';
        
        return view('abcc.account.edit', compact('customer_user', 'customer', 'aBook', 'tab_index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer      = Auth::user()->customer;

        return view('abcc.addresses.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer      = Auth::user()->customer;

        $this->validate($request, Address::$rules);

        $address = $this->address->create($request->all());

        $customer->addresses()->save($address);

        return redirect()->route('abcc.customer.addresses.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $address->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer      = Auth::user()->customer;

        // https://www.neontsunami.com/posts/nested-route-model-binding-in-laravel
        // https://scotch.io/tutorials/cleaner-laravel-controllers-with-route-model-binding

        // We can't be sure that the post belongs to the user, so shall
        // we re-fetch the model through the association?
        // $address = $customer->addresses()->find($address->id) ?? abort(404);

        // Is it a legal address?
        $address = $customer->addresses()->find($id);
        if( !$address )
            return redirect()->route('abcc.customer.addresses.index')
                    ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));

        return view('abcc.addresses.edit', compact('customer', 'address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $customer      = Auth::user()->customer;

        // Is it a legal address?
        $address = $customer->addresses()->find($id);
        if( !$address )
            return redirect()->route('abcc.customer.addresses.index')
                    ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));

        $this->validate($request, Address::$rules);

        $address->update($request->all());


        return redirect()->route('abcc.customer.addresses.index')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function updateDefaultAddresses(Request $request)
    {
        $customer      = Auth::user()->customer;
        $customer_id = $customer->id;

        // Need custom validator: address must be in table addresses, AND should belong to Customer
        $rule = [
                    'required',
                    Rule::exists('addresses', 'id')->where(function ($query) use ($customer_id) {
                        $query->where('addressable_id', $customer_id)
                        ->where('addressable_type', Customer::class)
                        ->where('deleted_at', null);
                    }),
                ];

        $rules = [
                            'invoicing_address_id' => $rule,
                            'shipping_address_id'  => $rule,
                ];

        $this->validate($request, $rules);

        $customer->update($request->only(['invoicing_address_id', 'shipping_address_id']));

        return redirect()->route('abcc.customer.addresses.index')
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer      = Auth::user()->customer;

        // Is it a legal address?
        $address = $customer->addresses()->find($id);
        if( !$address )
            return redirect()->route('abcc.customer.addresses.index')
                    ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));

        $address->delete();
        
        return redirect()->route('abcc.customer.addresses.index')
            ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') );
    }
}


/*
Actions Handled By Resource Controller

Verb        URI                     Action      Route Name

GET         /photos                 index       photos.index
GET         /photos/create          create      photos.create
POST        /photos                 store       photos.store
GET         /photos/{photo}         show        photos.show
GET         /photos/{photo}/edit    edit        photos.edit
PUT/PATCH   /photos/{photo}         update      photos.update
DELETE      /photos/{photo}         destroy     photos.destroy

*/
