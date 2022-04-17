<?php

namespace App\Http\Controllers;

use App\Models\DeliverySheet;
use Illuminate\Http\Request;

class DeliverySheetsController extends Controller
{
   protected $deliverysheet;

   public function __construct(DeliverySheet $deliverysheet)
   {
        $this->deliverysheet = $deliverysheet;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliverysheets = $this->deliverysheet->orderBy('id', 'desc')->get();

        return view('delivery_sheets.index', compact('deliverysheets'));
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
     * @param  \App\Models\DeliverySheet  $deliverysheet
     * @return \Illuminate\Http\Response
     */
    public function show(DeliverySheet $deliverysheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliverySheet  $deliverysheet
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliverySheet $deliverysheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliverySheet  $deliverysheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliverySheet $deliverysheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliverySheet  $deliverysheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliverySheet $deliverysheet)
    {
        //
    }
}
