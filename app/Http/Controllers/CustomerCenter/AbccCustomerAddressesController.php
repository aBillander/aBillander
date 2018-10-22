<?php

namespace App\Http\Controllers\CustomerCenter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Customer;
use App\Address;

class AbccCustomerAddressesController // extends Controller
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
    public function index(Request $request)
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
    public function create($customerId, Request $request)
    {
        $customer      = Auth::user()->customer;

        $customer = $this->customer->findOrFail($customerId);

        return view('abcc.addresses.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($customerId, Request $request)
    {
        $customer = $this->customer->findOrFail($customerId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Address::$rules);

        $address = $this->address->create($request->all());

        $customer->addresses()->save($address);

        return redirect($back_route)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));

        return redirect()->route('abcc.customer_addresses.index')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $address->id], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show($customerId, Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address, Request $request)
    {
        $customer      = Auth::user()->customer;

        // https://www.neontsunami.com/posts/nested-route-model-binding-in-laravel
        // https://scotch.io/tutorials/cleaner-laravel-controllers-with-route-model-binding

        // We can't be sure that the post belongs to the user, so shall
        // we re-fetch the model through the association?
        $address = $customer->addresses()->findOrFail($address->id) ?? abort(404);;

 //       $address = $this->address->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        return view('abcc.addresses.edit', compact('customer', 'address', 'back_route'));

        return view('abcc.customer_addresses.edit', compact(''));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $customerId, Address $address)
    {
 //       $address = $this->address->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Address::$rules);

        $address->update($request->all());

        return redirect($back_route)
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerId, Address $address, Request $request)
    {
 //       $address = $this->address->findOrFail($id);
        $back_route = $request->input('back_route', '');

        $address->delete();
        
        return redirect( $back_route )
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
