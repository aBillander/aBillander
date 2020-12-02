<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Configuration;
use App\Context;
use App\Product;
use App\SupplierShippingSlipLine as DocumentLine;

use App\Lot;
use App\LotItem;

use App\Traits\DateFormFormatterTrait;

class SupplierShippingSlipLineLotsController extends Controller 
{

   use DateFormFormatterTrait;

   protected $document_line;
//   protected $taxrule;

   public function __construct(DocumentLine $document_line)
   {
        $this->document_line = $document_line;
        // $this->taxrule = $taxrule;
   }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($lineId)
    {
        // return $lineId.l('xxx').snake_case('SupplierShippingSlipLineLots');

        $document_line = $this->document_line->with('lotitems')->findOrFail($lineId);

        // abi_r($document_line->lots);die();

        // $taxrules = $this->taxrule->with('country')->with('state')->where('tax_id', '=', $taxId)->orderBy('position', 'asc')->orderBy('name', 'asc')->get();

        return view('supplier_shipping_slip_line_lots._panel_document_line_lots', compact('document_line'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($lineId)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($lineId, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['expiry_at'], $request );

//        return $request->toArray();

        $document_line = $this->document_line->with('document')->with('product')->with('lotitems')->findOrFail($lineId);

        // Should validate data... But I am lazy today :(
        // Lot number should be unique
        if ( Lot::where('reference', $request->input('reference'))->exists() )
        {
            return response()->json( [
                    'msg' => 'KO',
                    'data' => $request->toArray(),
                    'error' => l('Duplicate Lot Number: :lot', ['lot' => $request->input('reference')], 'lots')
            ] );
        }

        // Should validate date anf quantity perhaps... But I am lazy today :(


        $lot_params = [
            'reference' => $request->input('reference'),
            'product_id' => $document_line->product_id, 
//            'combination_id' => ,
            'quantity_initial' => $request->input('quantity'), 
            'quantity' => $request->input('quantity'), 
            'measure_unit_id' => $document_line->measure_unit_id, 
//            'package_measure_unit_id' => , 
//            'pmu_conversion_rate' => ,
            'manufactured_at' => null, 
            'expiry_at' => $request->input('expiry_at'),
            'blocked' => 1,                                 // Will be unblocked when Shipping Slip is closed
            'notes' => '',

            'warehouse_id' => $document_line->document->warehouse_id,
        ];

        // Time for "some magic"
        // Create Lot
        $lot = Lot::create($lot_params);

        $lot_item = LotItem::create(['lot_id' => $lot->id]);
        $document_line->lotitems()->save($lot_item);

        return response()->json( [
                'msg' => 'OK',
                'data' => $request->toArray(),
        ] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $lotId
     * @return Response
     */
    public function show($lineId, $lotId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $lotId
     * @return Response
     */
    public function edit($lineId, $lotId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $lotId
     * @return Response
     */
    public function update($lineId, $lotId, Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $lotId
     * @return Response
     */
    public function destroy($lineId, $lotId)
    {
        $document_line = $this->document_line->with('lotitems')->with('lotitems.lot')->findOrFail($lineId);

        // return [$lineId, $lotId];

        // Should validate data... But I am lazy today :(

        $lot_item = $document_line->lotitems->where('lot_id', $lotId)->first();

        if ($lot_item)
        {
            $lot_item->lot->delete();
            $lot_item->delete();

            return response()->json( [
                    'msg' => 'OK',
                    'data' => [$lineId, $lotId],
            ] );
        }


        return response()->json( [
                'msg' => 'KO',
                'data' => [$lineId, $lotId],
        ] );
    }

}