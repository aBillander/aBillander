<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ShippingMethod;
use App\Models\ShippingMethodTableLine;

class ShippingMethodTableLinesController extends Controller
{
   protected $shippingmethod;
   protected $shippingmethodtableline;

   public function __construct(ShippingMethod $shippingmethod, ShippingMethodTableLine $shippingmethodtableline)
   {
        $this->shippingmethod = $shippingmethod;
        $this->shippingmethodtableline = $shippingmethodtableline;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($shippingmethodId)
    {
        $shippingmethod = $this->shippingmethod->find($shippingmethodId);
        // $lines = $this->shippingmethodtableline->with('country')->with('state')->where('shipping_method_id', '=', $shippingmethodId)->orderBy('amount', 'asc')->get();

        // return $shippingmethodId;

        // $shippingmethod = null;
        $lines = collect([]);

        return view('shipping_method_table_lines.index', compact('shippingmethod', 'lines'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
