<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\Contact;

class CustomerContactsController extends  Controller
{


   protected $customer;
   protected $contact;

   public function __construct(Customer $customer, Contact $contact)
   {
        $this->customer = $customer;
        $this->contact = $contact;
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
        $customer = $this->customer->findOrFail($customerId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        return view('customer_contacts.create', compact('customer', 'back_route'));
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

        $this->validate($request, Contact::$rules);

        $contact = $this->contact->create($request->all());

        $customer->contacts()->save($contact);

        return redirect($back_route)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $contact->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function show($customerId, $id)
    {
        return $this->edit($customerId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function edit($customerId, $id, Request $request)
    {
        $customer = $this->customer->findOrFail($customerId);
        $contact = $this->contact->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        return view('customer_contacts.edit', compact('customer', 'contact', 'back_route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function update($customerId, $id, Request $request)
    {
        $contact = $this->contact->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Contact::$rules);

        $contact->update($request->all());

        return redirect($back_route)
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $contact->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerId, $id, Request $request)
    {
        $contact = $this->contact->findOrFail($id);
        $back_route = $request->input('back_route', '');

        try {

            $contact->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }
        
        return redirect( $back_route )
            ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') );
    }
}
