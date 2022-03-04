<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Configuration;
use App\Context;
use App\Product;
use App\ProductionOrderLine as DocumentLine;

use App\Lot;
use App\LotItem;

use App\Traits\DateFormFormatterTrait;

class ProductionOrderLineLotsController extends Controller 
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
        // return $lineId.l('xxx').\Str::snake('CustomerShippingSlipLineLots');

        $document_line = $this->document_line->with('document')->with('lotitems')->findOrFail($lineId);

        // abi_r($document_line->lots);die();

        // $taxrules = $this->taxrule->with('country')->with('state')->where('tax_id', '=', $taxId)->orderBy('position', 'asc')->orderBy('name', 'asc')->get();

        return view('production_order_line_lots._panel_document_line_lots', compact('document_line'));
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
/* * /
            return response()->json( [
                    'msg' => 'KOK',
                    'success' => 'KOK',
                    'message' => $request->toArray(),
                    'data' => $request->toArray(),
            ] );
/ * */        

        $document_line = $this->document_line
                                    ->with('document')
                                    ->with('product')
                                    ->with('lotitems')
                                    ->findOrFail($lineId);
        
        // Get Lot IDs 
        $lot_group = $request->input('lot_group', []);

        if ( count( $lot_group ) == 0 )
            return response()->json( [
                'success' => 'OKKO',
                'message' => l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $lineId], 'layouts'),
    //            'data' => $customeruser->toArray()
            ] );

        // So far, so good
        $product = $document_line->product;

        // Should remove lotitems first or will be duplicated
        $document_line->lotitems->each(function($item) {
                $item->delete();
            });

        $lot_amount = $request->input('lot_amount', []);

        // Get Lots
        $product_id = $product->id;
        $sort_order = $product->lot_policy == 'FIFO' ? 'ASC' : 'DESC';
        $lots = Lot::
                      whereHas('product', function ($query) use ($product_id) {
                            $query->where('id', $product_id);
                        })
                    ->where('quantity', '>', 0)     // <= allocable quantity wil be checked later
//                  ->filter( $request->all() )
//                    ->with('customerinvoice')
//                    ->where('payment_type', 'receivable')
                    ->whereIn('id', $lot_group)
                    ->orderBy('expiry_at', $sort_order)
                    ->get();

/* Skip by now

        // Check Quantity!
        $detail_quantity = [];
        foreach ($lots as $lot) {
            //
            // Only unallocated quantity
            $lot_available_qty = $lot->quantity - $lot->allocatedQuantity();
            if ($lot_available_qty < 0.0)
                $lot_available_qty = 0.0;

            $amount = array_key_exists($lot->id, $lot_amount) ? 
                    (float) $lot_amount[$lot->id] : 
                    $lot_available_qty;
            
            if ($amount > $lot_available_qty) $amount = $lot_available_qty;
            if ($amount <= 0.0              ) $amount = $lot_available_qty;

            $detail_quantity[$lot->id] = $amount;
        }

        // abi_r($detail_quantity);

        $balance = array_sum($detail_quantity) - $product->measureunit->quantityable($document_line->required_quantity);

        if ( $balance != 0 )
            return response()->json( [
                'success' => 'KO',
                'message' => l('The Quantity of the selected Lots ( :selected ) do not match the value of the Line ( :quantity ) &#58&#58 (:id) ', ['id' => $lineId, 'selected' => $document_line->measureunit->quantityable(array_sum($detail_quantity)), 'quantity' => $document_line->measureunit->quantityable($document_line->required_quantity)], 'lots'),
            ] );
*/


        $detail_quantity = $lot_amount; // Since previous block is commented
        foreach ($lots as $lot) {
            # code...
            // Check
            if ( !array_key_exists($lot->id, $lot_amount) )
                continue;

            $data = [
                'lot_id' => $lot->id,
                'is_reservation' => 1,
                'quantity' => $detail_quantity[$lot->id],
            ];

            $lot_item = LotItem::create( $data );
            $document_line->lotitems()->save($lot_item);
        }

        // Update Line real_quantity
        $document_line->load('lotitems');

        $document_line->update(['real_quantity' => $document_line->lotitems->sum('quantity')]);


/*
        // Lot number
        // Work in progress. Assume one lot and no quantity
        $lot_reference = $lot_references;
        $lot = Lot::where('reference', $lot_reference)->where ('product_id', $request->input('product_id'))->first();
        if ( !$lot )
        {
            return response()->json( [
                    'msg' => 'KO',
                    'data' => $request->toArray(),
                    'error' => l('Invalid Lot Number: :lot', ['lot' => $lot_reference], 'lots')
            ] );
        }
        

        $lot_item = LotItem::create(['lot_id' => $lot->id]);
        $document_line->lotitems()->save($lot_item);
*/
        return response()->json( [
                'success' => 'OK',
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
//            $lot_item->lot->delete();
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