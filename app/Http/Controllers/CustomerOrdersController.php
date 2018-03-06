<?php

namespace App\Http\Controllers;

use App\CustomerOrder;
use Illuminate\Http\Request;

class CustomerOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerOrder $customerOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerOrder $customerOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerOrder $customerOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerOrder $customerOrder)
    {
        //
    }


/* ********************************************************************************************* */    


    public function move(Request $request, $id)
    {
        $order = \App\CustomerOrder::findOrFail($id);

        $order->update(['production_sheet_id' => $request->input('production_sheet_id')]);

        if ( $request->input('stay_current_sheet', 0) )
            $sheet_id = $request->input('current_production_sheet_id');
        else
            $sheet_id = $request->input('production_sheet_id');

        return redirect('productionsheets/'.$sheet_id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $sheet_id], 'layouts') . $request->input('name', ''));
    }

    public function unlink(Request $request, $id)
    {
        $order = \App\CustomerOrder::findOrFail($id);

        // Destroy Order Lines
        foreach( $order->customerorderlines as $line ) {
            $line->delete();
        }

        // Destroy Order
        $order->delete();

        $sheet_id = $request->input('current_production_sheet_id');

        return redirect('productionsheets/'.$sheet_id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    /**
     * AJAX Stuff.
     *
     * 
     */
    public function getOrderLines($id)
    {
        $order = \App\CustomerOrder::with('CustomerOrderLines')
                        ->with('CustomerOrderLines.product')
                        ->findOrFail($id);

        return view('production_sheets.ajax._panel_customer_order_lines', compact('order'));
    }
}
