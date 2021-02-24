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
                      ->orderBy('ps.due_date', 'DESC')
                      ->orderBy('poid', 'DESC');

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
     * @param  \App\ProductionOrder  $productionorder
     * @return \Illuminate\Http\Response
     */
    public function show(ProductionOrder $productionorder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductionOrder  $productionorder
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductionOrder $productionorder, Request $request)
    {
        return redirect()->route('productionsheet.productionorders', [$productionorder->production_sheet_id])
                ->with('info', l('Compruebe la Orden de Fabricación &#58&#58 (:id) ', ['id' => $productionorder->id]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductionOrder  $productionorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductionOrder $productionorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductionOrder  $productionorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductionOrder $productionorder)
    {
        //
    }


    /**
     * Manage Status.
     *
     * ******************************************************************************************************************************* *
     * 
     */


    protected function finish(ProductionOrder $productionorder)
    {
        // Can I?
        if ( $productionorder->lines->count() == 0 )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $productionorder->id], 'layouts').' :: '.l('Document has no Lines', 'layouts'));
        }

        // onhold?
/*
        if ( $productionorder->onhold )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $productionorder->id], 'layouts').' :: '.l('Document is on-hold', 'layouts'));
        }
*/

        // Close
        if ( $productionorder->close() )
            return redirect()->back()           // ->route($this->model_path.'.index')
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $productionorder->id], 'layouts').' ['.$productionorder->product_reference.']');
        

        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $productionorder->id], 'layouts'));
    }


    protected function unfinish(ProductionOrder $productionorder)
    {

        if ( $productionorder->status != 'finished' )
        {
            return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $productionorder->id], 'layouts').' :: '.l('Document is not closed', 'layouts'));
        }

        // Unclose (back to "confirmed" status)
        if ( $productionorder->unfinish() )
            return redirect()->back()
                    ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $productionorder->id], 'layouts').' ['.$productionorder->product_reference.']');


        return redirect()->back()
                ->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $productionorder->id], 'layouts'));
    }


/* ********************************************************************************************* */    


    public function productionsheetEdit(Request $request, $id)
    {
        $order = \App\ProductionOrder::findOrFail($id);

        $need_update = !( $order->planned_quantity == $request->input('planned_quantity') );

//        abi_r($order->planned_quantity. '==' .$request->input('planned_quantity'), true);

//        abi_r( $request->all() );die();

        $order->update( $request->all() );

        if ($need_update) $order->updateLines();
/*
        if ( $request->input('stay_current_sheet', 1) )
            $sheet_id = $request->input('current_production_sheet_id');
        else
            $sheet_id = $request->input('production_sheet_id');
*/
        $sheet_id = $request->input('current_production_sheet_id');

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $sheet_id], 'layouts') . $request->input('name', ''));
    }

    public function productionsheetDelete(Request $request, $id)
    {
        $order = \App\ProductionOrder::findOrFail($id);

        $order->deleteWithLines();
/*
        // Destroy Order Lines
        if ($order->productionorderlines()->count())
            foreach( $order->productionorderlines as $line ) {
                $line->delete();
            }

        // Destroy Order
        $order->delete();
*/
        $sheet_id = $request->input('current_production_sheet_id');

        return redirect()->back()
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
                                ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();
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



        $sheet = \App\ProductionSheet::with('productionorders')->findOrFail( $data['production_sheet_id'] );
        $pID = $data['product_id'];

        $needle = $sheet->productionorders->first(function($item) use ($pID) {
            return $item->product_id == $pID;
        });
        

        if (0)         // Skip
            if ($needle) return ['status' => 'ERROR', 'message' => 'No se puede crear una Orden de Fabricación para este Producto porque ya existe una.'];

        // So far, so good:
        $order = \App\ProductionOrder::createWithLines( $data );

        if ($order) return ['status' => 'OK'];

        // No BOM, so delete
//        $order->deleteWithLines();

        return ['status' => 'ERROR', 'message' => 'No se puede crear una Orden de Fabricación para este Producto porque no se ha encontrado una Receta Asociada.'];

/*
        return redirect()->back()
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $order->id], 'layouts') . $request->input('due_date'));
*/
    }
}
