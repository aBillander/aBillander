<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Address;

class SupplierAddressesController extends  Controller
{


   protected $supplier;
   protected $address;

   public function __construct(Supplier $supplier, Address $address)
   {
        $this->supplier = $supplier;
        $this->address = $address;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($supplierId)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($supplierId, Request $request)
    {
        $supplier = $this->supplier->findOrFail($supplierId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        return view('addresses.create', compact('supplier', 'back_route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($supplierId, Request $request)
    {
        $supplier = $this->supplier->findOrFail($supplierId);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        $this->validate($request, Address::$rules);

        $address = $this->address->create($request->all());

        $supplier->addresses()->save($address);

        return redirect($back_route)
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $address->id], 'layouts') . $request->input('alias'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function show($supplierId, $id)
    {
        return $this->edit($supplierId, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function edit($supplierId, $id, Request $request)
    {
        $supplier = $this->supplier->findOrFail($supplierId);
        $address = $this->address->findOrFail($id);
        $back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;

        return view('addresses.supplier.edit', compact('supplier', 'address', 'back_route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function update($supplierId, $id, Request $request)
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
     * @param  \App\Models\StockCountLine  $stockCountLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($supplierId, $id, Request $request)
    {
        $address = $this->address->findOrFail($id);
        $back_route = $request->input('back_route', '');

        $address->delete();
        
        return redirect( $back_route )
            ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') );
    }
}
