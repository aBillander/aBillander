<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ProductionOrder;
use Form, DB;

class ProductionOrdersController extends Controller
{


   protected $productionorder;

   public function __construct(ProductionOrder $productionorder)
   {
        $this->productionorder = $productionorder;
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->productionorder->filter( $request->all() )
                      ->select('*', 'production_orders.id AS poid')
                      ->with('workcenter')
                      ->with('productionsheet')
                      ->join('production_sheets AS ps', 'production_orders.production_sheet_id', '=', 'ps.id')
                      ->orderBy('ps.due_date', 'DESC');

// See: https://laracasts.com/discuss/channels/eloquent/eager-loading-cant-orderby
// See: https://stackoverflow.com/questions/18861186/eloquent-eager-load-order-by
                      

        $orders = $orders->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );

        $orders->setPath('productionorders');     // Customize the URI used by the paginator

        return view('production_orders.index', compact('orders'));
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
     * @param  \App\ProductionOrder  $productionOrder
     * @return \Illuminate\Http\Response
     */
    public function show(ProductionOrder $productionOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductionOrder  $productionOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductionOrder $productionOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductionOrder  $productionOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductionOrder $productionOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductionOrder  $productionOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductionOrder $productionOrder)
    {
        //
    }


/* ********************************************************************************************* */    


    public function productionsheetEdit(Request $request, $id)
    {
        $order = \App\ProductionOrder::findOrFail($id);

        $need_update = ( $order->planned_quantity == $request->input('planned_quantity') );

        $order->update( $request->all() );

        if ($need_update) $order->updateLines();

        if ( $request->input('stay_current_sheet', 1) )
            $sheet_id = $request->input('current_production_sheet_id');
        else
            $sheet_id = $request->input('production_sheet_id');

        return redirect('productionsheets/'.$sheet_id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $sheet_id], 'layouts') . $request->input('name', ''));
    }

    public function productionsheetDelete(Request $request, $id)
    {
        $order = \App\ProductionOrder::findOrFail($id);

        // Destroy Order Lines
        if ($order->productionorderlines()->count())
            foreach( $order->productionorderlines as $line ) {
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
    public function getOrder($id)
    {
        $order = \App\ProductionOrder::with('ProductionOrderLines')
                        ->with('ProductionOrderLines.product')
                        ->with('ProductionOrderLines.product.measureunit')
                        ->findOrFail($id);

        return view('production_sheets.ajax._panel_production_order_lines', compact('order'));
    }


    /**
     * AJAX Stuff.
     *
     * 
     */

    public function searchProduct(Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'work_center_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->isManufactured()
//                                ->with('measureunit')
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );
/*
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                    'id' => $product->id,
                    'value' => '['.$product->reference.'] '.$product->name,
                    'reference'       => $product->reference,
                    'measure_unit_id' => $product->measure_unit_id,
            ];
        }
*/

        return response( $products );
    }

    public function storeOrder(Request $request)
    {
        // Let's see what we have here
        $data = $request->all();  // return response()->json(['mensaje' => $data]);


        $product = \App\Product::with('bomitems')->with('boms')->findOrFail( $data['product_id'] );
        $bomitem = $product->bomitem();
        $bom     = $product->bom();
        // Adjust Manufacturing baych size
        $nbt = ceil($data['planned_quantity'] / $product->manufacturing_batch_size);
        $order_quantity = $nbt * $product->manufacturing_batch_size;

        $order = \App\ProductionOrder::create([
            'created_via' => 'manual',
            'status' => 'released',

            'product_id' => $product->id,
            'product_reference' => $product->reference,
            'product_name' => $product->name,

            'planned_quantity' => $order_quantity,
            'product_bom_id' => $bom->id,

            'due_date' => $data['due_date'],
            'notes' => $data['notes'],

            'work_center_id' => $data['work_center_id'],
//            'warehouse_id' => '',
            'production_sheet_id' => $data['production_sheet_id'],
        ]);


        // Order lines
        // BOM quantities
        $line_qty = $order_quantity * $bomitem->quantity / $bom->quantity;

        foreach( $bom->BOMlines as $line ) {
            
             $line_product = \App\Product::with('measureunit')->findOrFail( $line->product_id );

             $order_line = \App\ProductionOrderLine::create([
                'type' => 'product',
                'product_id' => $line_product->id,
                'reference' => $line_product->reference,
                'name' => $line_product->name, 

//                'base_quantity', 
                'required_quantity' => $line_qty * $line->quantity * (1.0 + $line->scrap/100.0), 
                // Assume 1 product == 1 measure unit O_O
                'measure_unit_id' => $line_product->measure_unit_id,
//                'warehouse_id'
            ]);

            $order->productionorderlines()->save($order_line);
        }


        return ['OK'];
/*
        return redirect()->back()
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $order->id], 'layouts') . $request->input('due_date'));
*/
    }
}
