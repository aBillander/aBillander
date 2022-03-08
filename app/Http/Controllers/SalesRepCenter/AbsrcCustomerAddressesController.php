<?php

namespace App\Http\Controllers\SalesRepCenter;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Mail;

use App\Customer;
use App\Address;

class AbsrcCustomerAddressesController extends  Controller
{


   protected $customer;
   protected $address;

   public function __construct(Customer $customer, Address $address)
   {
        $this->customer = $customer;
        $this->address = $address;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($customerId)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($customerId, Request $request)
    {
        $customer = $this->customer->ofSalesRep()->findOrFail($customerId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        return view('absrc.addresses.create', compact('customer', 'back_route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($customerId, Request $request)
    {
        $customer = $this->customer->ofSalesRep()->findOrFail($customerId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Address::$rules);

        $address = $this->address->create($request->all());

        $customer->addresses()->save($address);

        return redirect($back_route)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function show($customerId, $id)
    {
        return $this->edit($customerId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function edit($customerId, $id, Request $request)
    {
        $customer = $this->customer->ofSalesRep()->findOrFail($customerId);
        // To do: Little checking: Address must by owned by customer, and customer by salesrep !!!
        $address = $this->address->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        return view('absrc.addresses.edit', compact('customer', 'address', 'back_route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function update($customerId, $id, Request $request)
    {
        $address = $this->address->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Address::$rules);

        $address->update($request->all());

        return redirect($back_route)
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerId, $id, Request $request)
    {
        $address = $this->address->findOrFail($id);
        $back_route = $request->input('back_route', '');

        // To do: Little checking: Address must by owned by customer, and customer by salesrep !!!
        
        $address->delete();
        
        return redirect( $back_route )
            ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') );
    }
}
