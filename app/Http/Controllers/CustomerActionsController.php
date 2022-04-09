<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\Action;

class CustomerActionsController extends  Controller
{


   protected $customer;
   protected $action;

   public function __construct(Customer $customer, Action $action)
   {
        $this->customer = $customer;
        $this->action = $action;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($customerId)
    {        
        $customer = $this->customer
                         ->with('actions')
                         ->findOrFail($customerId);

        abi_r($customer->actions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($customerId, Request $request)
    {
        $customer = $this->customer
                         ->with('actions')
                         ->findOrFail($customerId);
        
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $action_typeList = Action::getTypeList();

        $customer_addressList = $customer->getAddressList();

        return view('customer_actions.create', compact('customer', 'back_route', 'action_typeList', 'customer_addressList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($customerId, Request $request)
    {
        $customer = $this->customer
                         ->with('actions')
                         ->findOrFail($customerId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Action::$rules);

        $action = $this->action->create($request->all());

        $customer->actions()->save($action);

        if ($request->input('is_primary') || ($customer->actions()->count() == 1)) {
            $customer->setPrimaryAction($action);
        }

        return redirect($back_route)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $action->id], 'layouts') . $request->input('alias'));
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
        $customer = $this->customer
                         ->with('actions')
                         ->findOrFail($customerId);
        $action = $this->action->findOrFail($id);
        
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $action_typeList = Action::getTypeList();

        $customer_addressList = $customer->getAddressList();

        return view('customer_actions.edit', compact('customer', 'action', 'back_route', 'action_typeList', 'customer_addressList'));
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
        $action = $this->action
                         ->with('customer')
                         ->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Action::$rules);

        $action->update($request->all());

        if ($request->input('is_primary') || ($customer->actions()->count() == 1)) {
            $action->customer->setPrimaryAction($action);
        }

        return redirect($back_route)
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $action->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($customerId, $id, Request $request)
    {
        $action = $this->action->findOrFail($id);
        $back_route = $request->input('back_route', '');

        try {

            $action->delete();
            
        } catch (\Exception $e) {

            return redirect()->back()
                    ->with('error', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts').$e->getMessage());
            
        }
        
        return redirect( $back_route )
            ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') );
    }
}
