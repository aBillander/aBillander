<?php

namespace App\Http\Controllers;

use App\DeliveryRoute;
use App\DeliveryRouteLine;
use Illuminate\Http\Request;

class DeliveryRouteLinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($deliveryrouteId)
    {
        $deliveryroute = DeliveryRoute::with('deliveryroutelines')->findOrFail($deliveryrouteId);
        $deliveryroutelines = $deliveryroute->deliveryroutelines;

        return view('delivery_route_lines.index', compact('deliveryroute', 'deliveryroutelines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($deliveryrouteId)
    {
        $deliveryroute = DeliveryRoute::with('deliveryroutelines')->findOrFail($deliveryrouteId);
        return view('delivery_route_lines.create', compact('deliveryroute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($deliveryrouteId, Request $request)
    {
        $deliveryroute = DeliveryRoute::with('deliveryroutelines')->findOrFail($deliveryrouteId);
        $this->validate($request, DeliveryRouteLine::$rules);

        // Handy conversions
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $deliveryroute->deliveryroutelines->max('line_sort_order') + 10  ] );


        $deliveryrouteline = DeliveryRouteLine::create($request->all());

        $deliveryroute->deliveryroutelines()->save($deliveryrouteline);

        return redirect('deliveryroutes/'.$deliveryrouteId.'/deliveryroutelines')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $deliveryrouteline->id], 'layouts') . $request->input('line_sort_order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DeliveryRouteLine  $deliveryrouteline
     * @return \Illuminate\Http\Response
     */
    public function show($deliveryrouteId, DeliveryRouteLine $deliveryrouteline)
    {
        return $this->edit($deliveryrouteId, $deliveryrouteline);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeliveryRouteLine  $deliveryrouteline
     * @return \Illuminate\Http\Response
     */
    public function edit($deliveryrouteId, DeliveryRouteLine $deliveryrouteline)
    {
        $deliveryroute = DeliveryRoute::with('deliveryroutelines')->findOrFail($deliveryrouteId);
        // $taxrule = $this->taxrule->findOrFail($id);

        $deliveryrouteline->load(['customer', 'address']);

        return view('delivery_route_lines.edit', compact('deliveryroute', 'deliveryrouteline'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeliveryRouteLine  $deliveryrouteline
     * @return \Illuminate\Http\Response
     */
    public function update($deliveryrouteId, Request $request, DeliveryRouteLine $deliveryrouteline)
    {
        $deliveryroute = DeliveryRoute::with('deliveryroutelines')->findOrFail($deliveryrouteId);

        // Handy conversions
        $request->merge( ['customer_id'  => $deliveryrouteline->customer_id] );
        $request->merge( ['address_id'   => $deliveryrouteline->address_id ] );
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $deliveryroute->deliveryroutelines->max('line_sort_order') + 10  ] );
        

        $this->validate($request, DeliveryRouteLine::$rules);

        $deliveryrouteline->update($request->all());

        return redirect('deliveryroutes/'.$deliveryrouteId.'/deliveryroutelines')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $deliveryrouteline->id], 'layouts') . $request->input('line_sort_order'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeliveryRouteLine  $deliveryrouteline
     * @return \Illuminate\Http\Response
     */
    public function destroy($deliveryrouteId, DeliveryRouteLine $deliveryrouteline)
    {
        $id = $deliveryrouteline->id;

        $deliveryrouteline->delete();

        return redirect('deliveryroutes/'.$deliveryrouteId.'/deliveryroutelines')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }
}
