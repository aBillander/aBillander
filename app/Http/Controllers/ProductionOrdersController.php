<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Context;
use App\Configuration;
use App\WorkCenter;
use App\Warehouse;
use App\Product;

use App\ProductionOrder;
use App\ProductionOrderLine;

use Form, DB;

use Excel;

use App\Traits\DateFormFormatterTrait;
use App\Traits\ModelAttachmentControllerTrait;

class ProductionOrdersController extends Controller
{

   use DateFormFormatterTrait;
   use ModelAttachmentControllerTrait;

   protected $productionorder;

   public function __construct(ProductionOrder $productionorder, ProductionOrderLine $productionorder_line)
   {
        $this->productionorder = $productionorder;
        $this->productionorder_line = $productionorder_line;
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
        return redirect()->route('productionorders.edit', [$productionorder->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductionOrder  $productionorder
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductionOrder $productionorder, Request $request)
    {

        // Dates (cuen)
        $this->addFormDates( ['due_date', 'finish_date'], $productionorder );

        $document = $productionorder;

        $warehouseList = Warehouse::selectorList();

        $work_centerList = WorkCenter::pluck('name', 'id')->toArray();


        return view('production_orders.edit', compact('document', 'warehouseList', 'work_centerList'));

        
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
        // Dates (cuen)
        $this->mergeFormDates( ['due_date', 'finish_date'], $request );

        $rules = $this->productionorder::$rules;

        $this->validate($request, $rules);

        /* ------------------------------------------------------------- */

        $document = $productionorder;

        // $need_update_totals = (
        //     $request->input('document_ppd_percent', $document->document_ppd_percent) != $document->document_ppd_percent 
        // ) ? true : false;

        $document->fill($request->all());

        $document->save();

        // if ( $need_update_totals ) $document->makeTotals();

        // Move on
        $nextAction = $request->input('nextAction', '');

        // abi_r($request->all());die();

        switch ( $nextAction ) {
            case 'saveAndFinish':
                # code...
                $document->finish();

                return redirect()->route('productionorders.index')
                        ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
                break;
            
            case 'saveAndContinue':
                # code...

                break;
            
            default:
                # code...
                break;
        }

        return redirect()->back()
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));

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


    protected function finish(ProductionOrder $document)
    {
        abi_r($document); die();

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


    protected function unfinish(ProductionOrder $document)
    {
        abi_r($document); die();

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

        $search_parts = (bool) $request->input('search_parts', 0);

        $products = \App\Product::select('id', 'name', 'reference', 'work_center_id', 'manufacturing_batch_size', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->isManufactured( !$search_parts )
//                                ->isPartItem( $search_parts )     <= Any product can be a part of a BOM
                                ->with('measureunit')
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






/* ********************************************************************************************* */  


    /**
     * AJAX Stuff.
     *
     * 
     */


    public function getDocumentHeader($id)
    {
        // Some rework needed!!!

        $document = $this->productionorder
                        ->with('customer')
                        ->findOrFail($id);
        
        $customer = $document->customer;

        return 'Peo!';

        return view('production_orders._tab_edit_header', $this->modelVars() + compact('document', 'customer'));
    }

    
    public function getDocumentLines($id)
    {
//        $model = $this->getParentModelLowerCase();

//        return "$id - $model";

        $document = $this->productionorder
                        ->with('lines')
                        ->with('lines.product')
                        ->with('lines.measureunit')
                        ->with('lines.warehouse')
                        ->findOrFail($id);                        

        // Pre-process
        foreach ($document->lines as $line) {
            // code...
            if ( !$line->measure_unit_id )
            {
                $line->measure_unit_id = $line->product->measure_unit_id;
                $line->save();
                $line->measureunit = $line->product->measureunit;
            }
        }

        return view('production_orders._panel_document_lines', compact('document'));
    }

    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                $this->productionorder_line::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }

    public function getDocumentPayments($id)
    {
        $document = $this->productionorder
                        ->with('payments')
                        ->findOrFail($id);

        return view('production_orders._panel_document_payments', $this->modelVars() + compact('document'));
    }



    // Deprecated
    public function searchProduct_duplicate(Request $request)
    {

        $search = $request->term;

        $warehouse_id = (int) $request->input('warehouse_id', 0);

        if ( $warehouse_id > 0 )
        {
            $warehouse = Warehouse::findOrFail($warehouse_id);

            $products = $warehouse->products()
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get( );

        } else {

            $products = Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
//                                ->IsSaleable()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
//                                ->CheckStock()
//                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get( );
        }


//                                dd($products);

        return response( $products );
    }

    public function searchService(Request $request)
    {
        $search = $request->term;

        $products = Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
                                ->isService()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $products );
    }

    public function getProduct(Request $request)
    {        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
//        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $customer_id, $currency_id ] );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.measureunit')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('measureunit')->findOrFail(intval($product_id));
        }

        return response()->json( $product );
    }

    public function getProductPrices(Request $request)
    {
        
        // Request data
        $product_id      = $request->input('product_id');
        $combination_id  = $request->input('combination_id');
        $customer_id     = $request->input('customer_id');
        $recent_sales_this_customer = $request->input('recent_sales_this_customer', 0);
        $currency_id     = $request->input('currency_id', Context::getContext()->currency->id);
//        $currency_conversion_rate = $request->input('currency_conversion_rate', $currency->conversion_rate);

 //       return response()->json( [ $product_id, $combination_id, $customer_id, $currency_id ] );

        // Do the Mambo!
        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->with('product.tax')->findOrFail(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = Product::with('tax')->findOrFail(intval($product_id));
        }

        $category_id = $product->category_id;

        // Customer
        $customer = Customer::findOrFail(intval($customer_id));
        $customer_group_id = $customer->customer_group_id;
        
        // Currency
        $currency = Currency::findOrFail(intval($currency_id));
        $currency->conversion_rate = $request->input('conversion_rate', $currency->conversion_rate);

        // Pricelists
        $pricelists = $product->pricelists; //  PriceList::with('currency')->orderBy('id', 'ASC')->get();

        // Price Rules
        $customer_rules = $product->getPriceRulesByCustomer( $customer );
/*      Old stuff
        $customer_rules = PriceRule::
//                                  where('customer_id', $customer->id)
                                  where(function($q) use ($customer_id, $customer_group_id) {

                                    $q->where('customer_id', $customer_id);

                                    $q->orWhere('customer_group_id', NULL);

                                    $q->orWhere('customer_group_id', 0);

                                    if ( $customer_group_id > 0 )
                                    $q->orWhere('customer_group_id', $customer_group_id);

                                })
                                ->where(function($q) use ($product_id, $category_id) {

                                    $q->where('product_id', $product_id);

                                    // $q->orWhere('category_id', NULL);

                                    // $q->orWhere('category_id', 0);

                                    if ( $category_id > 0 )
                                    $q->orWhere('category_id', $category_id);

                                })
                                ->with('category')
                                ->with('product')
                                ->with('combination')
                                ->with('customergroup')
                                ->with('currency')
                                ->orderBy('product_id', 'ASC')
                                ->orderBy('customer_id', 'ASC')
                                ->orderBy('from_quantity', 'ASC')
                                ->take(7)->get();
*/

/*
        // Recent Sales
        $lines = CustomerOrderLine::where('product_id', $product->id)
                            ->with(["document" => function($q){
                                $q->where('customerorders.customer_id', $customer->id);
                            }])
                            ->with('document')
                            ->with('document.customer')
                            ->whereHas('document', function($q) use ($customer_id, $recent_sales_this_customer) {
                                    if ( $recent_sales_this_customer > 0 )
                                        $q->where('customer_id', $customer_id);
                                })
                            ->join('customer_orders', 'customer_order_lines.customer_order_id', '=', 'customer_orders.id')
                            ->select('customer_order_lines.*', 'customer_orders.document_date', \DB::raw('"customerorders" as route'))
                            ->orderBy('customer_orders.document_date', 'desc')
                            ->take(7)->get();
*/
        // Recent Sales
        $model = Configuration::get('RECENT_SALES_CLASS') ?: 'CustomerOrder';
        $class = '\App\\'.$model.'Line';
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);
        $tableLines = \Str::snake($model).'_lines';
        $lines = $class::where('product_id', $product->id)
                            ->with(["document" => function($q){
                                $q->where('customerorders.customer_id', $customer->id);
                            }])
                            ->with('document')
                            ->with('document.customer')
                            ->whereHas('document', function($q) use ($customer_id, $recent_sales_this_customer) {
                                    if ( $recent_sales_this_customer > 0 )
                                        $q->where('customer_id', $customer_id);
                                })
                            ->join($table, $tableLines.'.'.\Str::snake($model).'_id', '=', $table.'.id')
                            ->select($tableLines.'.*', $table.'.document_date', \DB::raw('"'.$route.'" as route'))
                            ->orderBy($table.'.document_date', 'desc')
                            ->take(7)->get();


        return view('production_orders._form_for_product_prices', $this->modelVars() + compact('product', 'pricelists', 'customer_rules', 'lines'));
    }

    public function getDocumentLine($document_id, $line_id)
    {
        $document_line = $this->productionorder_line
                        ->with('product')
                        ->with('measureunit')
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [] );
        
        if ( !$document_line->measure_unit_id )
        {
            $document_line->measure_unit_id = $document_line->product->measure_unit_id;
            $document_line->save();
            $document_line->measureunit = $document_line->product->measureunit;
        }

        return response()->json( $document_line->toArray() );
    }

    public function deleteDocumentLine($line_id)
    {

        $document_line = $this->productionorder_line
                        ->findOrFail($line_id);

        $document_line->delete();

        return response()->json( [
                'msg' => 'OK',
                'data' => $line_id,
        ] );
    }


    public function quickAddLines(Request $request, $document_id)
    {
        parse_str($request->input('product_id_values'), $output);
        $product_id_values = $output['product_id_values'];

        parse_str($request->input('combination_id_values'), $output);
        $combination_id_values = $output['combination_id_values'];

        parse_str($request->input('quantity_values'), $output);
        $quantity_values = $output['quantity_values'];

        // abi_r( $document_id );
        // abi_r($request->all());die();


        // Let's Rock!
        $document = $this->productionorder
                        ->findOrFail($document_id);

        // return $document;

        $line_sort_order = $document->getNextLineSortOrder();

        foreach ($product_id_values as $key => $pid) {
            # code...

            $line[] = $document->addProductLine( $pid, $combination_id_values[$key], $quantity_values[$key], ['line_sort_order' => $line_sort_order] );

            $line_sort_order += 10;

            // abi_r($line, true);
        }

        return response()->json( [
                'msg' => 'OK',
                'document' => $document_id,
                'data' => $line,
 //               'currency' => $line[0]->currency,
        ] );

    }





    public function getDocumentMaterials($id, Request $request)
    {
        $onhand_only = $request->input('onhand_only', 0);

        $document = $this->productionorder
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        if ($onhand_only)
            foreach ($document->lines as $line) {
                # code...
                if ( $line->type == 'product' ) {
                    $quantity = $line->required_quantity;
                    $onhand   = $line->product->quantity_onhand > 0 ? $line->product->quantity_onhand : 0;
    
                    $line->quantity_onhand =  $quantity > $onhand ? $onhand : $quantity;

                } else {
                    $line->quantity_onhand = $line->required_quantity;

                }
            }
        else
            foreach ($document->lines as $line) {
                # code...

                $line->quantity_onhand = $line->required_quantity;
            }

        $warehouseList = Warehouse::selectorList();

        return view('production_orders._panel_document_materials', compact('document', 'warehouseList'));
    }

    public function setDocumentMaterials($id, Request $request)
    {
        // abi_r($request->All());die();

        // $onhand_only = $request->input('onhand_only', 0);

        $document = $this->productionorder
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        $dispatch_warehouse = $request->input('dispatch_warehouse', []);
        $dispatch = $request->input('dispatch', []);

        foreach ($document->lines as $line) {
            // if ( array_key_exists($line->id, $dispatch)
            $line->warehouse_id  = $dispatch_warehouse[$line->id];
            $line->real_quantity = $dispatch[$line->id];

            $line->save();
        }

        return redirect()->back()
            ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts'));
    }


    public function getDocumentAvailabilityModal($id, Request $request)
    {
        $document = $this->productionorder
                        ->with('lines')
                        ->with('lines.product')
                        ->findOrFail($id);

        return view('production_orders._modal_document_availability_content', $this->modelVars() + compact('document'));
    }


/* ********************************************************************************************* */  


    /**
     * Forms Manager.
     *
     * 
     */


    public function FormForProduct( $action )
    {

        $warehouseList = Warehouse::selectorList();

        switch ( $action ) {
            case 'edit':
                # code...
                return view('production_orders._form_for_product_edit', compact('warehouseList'));
                break;
            
            case 'create':
                # code...
                return view('production_orders._form_for_product_create', compact('warehouseList'));
                break;
            
            default:
                # code...
                // Form for action not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $action
                    ] );
                break;
        }
        
    }

    public function storeDocumentLine(Request $request, $document_id)
    {
        // $line_type = $request->input('line_type', '');
        $line_type = 'product';         // So far...

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->storeDocumentLineProduct($request, $document_id);
                break;
            
            case 'service':
                # code...
                return $this->storeDocumentLineService($request, $document_id);
                break;
            
            case 'comment':
                # code...
                return $this->storeDocumentLineComment($request, $document_id);
                break;
            
            default:
                # code...
                // Document Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function storeDocumentLineProduct(Request $request, $document_id)
    {
        $document = $this->productionorder
                        ->with('measureunit')
                        ->find($document_id);


        $product_id     = $request->input('product_id');
        $combination_id = $request->input('combination_id', null);

        $product = Product::with('measureunit')->find($product_id);

        if ( !$document || !$product )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $document_id,
            ] );
        
        $quantity       = $request->input('required_quantity', 1.0);

        $warehouse_id   = $request->input('warehouse_id', Configuration::get('DEF_WAREHOUSE'));

        $params = [
            'line_sort_order' => $request->input('line_sort_order'),
            'type' => 'product',

            'product_id' => $product->id,
            'reference' => $product->reference,
            'name' => $product->name,

            'bom_line_quantity' => 0.0,     // BOM not used here
            'bom_quantity' => 0.0,          // BOM not used here

            'required_quantity' => $quantity,
//            'real_quantity' => 0.0,

            'measure_unit_id' => $product->measure_unit_id,

            'warehouse_id' => $warehouse_id,

            'store_mode' => $request->input('store_mode', ''),
        ];

        // More stuff
        if ($request->has('name')) 
            $params['name'] = $request->input('name');

        if ($request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');

        
        // Let's Rock!

        $store_mode = $request->input('store_mode', '');

        if ( $store_mode == 'asis' )
            // Force product price from imput
            // "DISABLED, so far" $document_line = $document->addProductAsIsLine( $product_id, $combination_id, $quantity, $params );
            ;   // Do nothing...
        else
            // Calculate product price according to Customer Price List and Price Rules
            $document_line = $document->addProductLine( $product_id, $combination_id, $quantity, $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }


    public function updateDocumentLine(Request $request, $line_id)
    {
        // $line_type = $request->input('line_type', '');
        $line_type = 'product';         // So far...

        switch ( $line_type ) {
            case 'product':
                # code...
                return $this->updateDocumentLineProduct($request, $line_id);
                break;
            
            case 'service':
            case 'shipping':
                # code...
                return $this->updateDocumentLineService($request, $line_id);
                break;
            
            case 'comment':
                # code...
                return $this->updateDocumentLineComment($request, $line_id);
                break;
            
            default:
                # code...
                // Document Line Type not supported
                return response()->json( [
                            'msg' => 'ERROR',
                            'data' => $request->all()
                    ] );
                break;
        }
    }

    public function updateDocumentLineProduct(Request $request, $line_id)
    {

        $params = [
        ];

        // More stuff
        if ($request->has('required_quantity')) 
            $params['required_quantity'] = $request->input('required_quantity');

        if ($request->has('warehouse_id')) 
            $params['warehouse_id'] = $request->input('warehouse_id');

        // Skip:
        if (0 && $request->has('line_sort_order')) 
            $params['line_sort_order'] = $request->input('line_sort_order');

        // Skip:
        if (0 && $request->has('measure_unit_id')) 
            $params['measure_unit_id'] = $request->input('measure_unit_id');


        // Let's Rock!
        $document_line = $this->productionorder_line
                        ->with( 'document' )
                        ->find($line_id);

        if ( !$document_line )
            return response()->json( [
                    'msg' => 'ERROR',
                    'data' => $line_id,
            ] );

        
        $document = $document_line->productionorder;
//        $document = $this->productionorder->where('id', $this->model_snake_case.'_id')->first();

        // Not so fast, Sony boy!!
        // $document_line = $document->updateProductLine( $line_id, $params );
        $document_line->update( $params );


        return response()->json( [
                'msg' => 'OK',
                'data' => $document_line->toArray()
        ] );
    }




    /*
    |--------------------------------------------------------------------------
    | Document presentation methods
    |--------------------------------------------------------------------------
    */
    
    
    protected function showPdf($id, Request $request)
    {
        // return $id;

        // PDF stuff
        try {
            $document = $this->productionorder
                            ->with('warehouse')
                            ->with('warehousecounterpart')
                            ->with('shippingmethod')
                            ->with('carrier')
                            ->with('template')
                            ->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect()->back()
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
        }

        // abi_r($document->hasManyThrough('App\CustomerInvoiceLineTax', 'App\CustomerInvoiceLine'), true);

        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;

        // Template
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_WAREHOUSE_SHIPPING_SLIP_TEMPLATE') );

        if ( !$t )
            return redirect()->route('warehouseshippingslips.edit', $id)
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');


        $template = $t->getPath( 'WarehouseShippingSlip' );

        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.

        if ($request->has('screen')) return view($template, compact('document', 'company'));
        
        
        // Catch for errors
        try{
                $pdf        = \PDF::loadView( $template, compact('document', 'company') )
                            ->setPaper( $paper, $orientation );
        }
        catch(\Exception $e){

                abi_r($template);
                abi_r($e->getMessage(), true);

                // return redirect()->route('customerorders.show', $id)
                //    ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').$e->getMessage());
        }

        // PDF stuff ENDS

        $pdfName    = \Str::singular('warehouseshippingslips').'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d');

        // Lets try another strategy
        if ( $document->document_reference ) {
            //
            $sanitizer = new \App\FilenameSanitizer( $document->document_reference );

            $sanitizer->stripPhp()
                ->stripRiskyCharacters()
                ->stripIllegalFilesystemCharacters('_');
                
            $pdfName = $sanitizer->getFilename();
        } else {
            //
            $pdfName = \Str::singular('warehouseshippingslips').'_'.'ID_' . (string) $document->id;
        }


        if ($request->has('screen')) return view($template, compact('document', 'company'));


        if ( $request->has('preview') ) 
        {
            //
        } else {
            // Dispatch event
            event( new \App\Events\WarehouseShippingSlipPrinted( $document ) );
        }
    

        return  $pdf->stream($pdfName . '.pdf');
        return  $pdf->download( $pdfName . '.pdf');
    }


    public function sendemail( $id, Request $request )
    {
        // $id = $request->input('id');

        // abi_r($id);die();

        // PDF stuff
        try {
            $document = $this->productionorder
                            ->with('warehouse')
                            ->with('warehousecounterpart')
                            ->with('shippingmethod')
                            ->with('carrier')
                            ->with('template')
                            ->findOrFail($id);

        } catch(ModelNotFoundException $e) {

            return redirect()->back()
                     ->with('error', l('The record with id=:id does not exist', ['id' => $id], 'layouts'));
            // return Redirect::to('invoice')->with('message', trans('invoice.access_denied'));
        }

        $document->close();

        if ( $document->status != 'closed' )
            return redirect()->back()
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document is NOT closed.');



        // $company = \App\Company::find( intval(Configuration::get('DEF_COMPANY')) );
        $company = \App\Context::getContext()->company;

        // Template
        // $seq_id = $this->sequence_id > 0 ? $this->sequence_id : Configuration::get('DEF_'.strtoupper( $this->getClassSnakeCase() ).'_SEQUENCE');
        $t = $document->template ?? 
             \App\Template::find( Configuration::getInt('DEF_'.strtoupper( 'WarehouseShippingSlip' ).'_TEMPLATE') );

        if ( !$t )
            return redirect()->back()
                ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'Document template not found.');


        $template = $t->getPath( 'WarehouseShippingSlip' ); 

        $paper = $t->paper;    // A4, letter
        $orientation = $t->orientation;    // 'portrait' or 'landscape'.


        // Catch for errors
        try{
                $pdf        = \PDF::loadView( $template, compact('document', 'company') )
//                          ->setPaper( $paper )
//                          ->setOrientation( $orientation );
                            ->setPaper( $paper, $orientation );
        }
        catch(\Exception $e){

//                abi_r($template);
//                abi_r($e->getMessage(), true);

                return redirect()->back()
                    ->with('error', l('Unable to load PDF Document &#58&#58 (:id) ', ['id' => $document->id], 'layouts').'<br />'.$e->getMessage());
        }

        // PDF stuff ENDS

        // MAIL stuff
        try {

            $pdfName    = \Str::singular('warehouseshippingslips').'_'.$document->secure_key . '_' . $document->document_date->format('Y-m-d');

            $pathToFile     = storage_path() . '/pdf/' . $pdfName .'.pdf';// die($pathToFile);
            $pdf->save($pathToFile);

            if ($request->isMethod('post'))
            {
                // ... this is POST method (call from popup)
                $subject = $request->input('email_subject');
            }
            if ($request->isMethod('get'))
            {
                // ... this is GET method (call from button)
                $subject = l('warehouseshippingslips'.'.default.subject :num :date', [ 'num' => $document->number, 'date' => abi_date_short($document->document_date) ], 'emails');
            }

            $template_vars = array(
                'company'       => $company,
                'invoice_num'   => $document->number,
                'invoice_date'  => abi_date_short($document->document_date),
                'invoice_total' => $document->as_money('total_tax_incl'),
                'custom_body'   => $request->input('email_body', ''),
//                'custom_subject' => $request->input('email_subject'),
                );

            $data = array(
                'from'     => $company->address->email,
                'fromName' => $company->name_fiscal,
                'to'       => $document->warehousecounterpart->address->email,
                'toName'   => $document->warehousecounterpart->name_fiscal,
                'subject'  => $subject,
                );

            

            // http://belardesign.com/2013/09/11/how-to-smtp-for-mailing-in-laravel/
            \Mail::send('emails.'.'warehouseshippingslips'.'.default', $template_vars, function($message) use ($data, $pathToFile)
            {
                $message->from($data['from'], $data['fromName']);

                $message->to( $data['to'], $data['toName'] )->bcc( $data['from'] )->subject( $data['subject'] );    // Will send blind copy to sender!
                
                $message->attach($pathToFile);

            }); 
            
            unlink($pathToFile);

        } catch(\Exception $e) {

 //               abi_r($e->getMessage(), true);

            return redirect()->back()->with('error', l('Your Document could not be sent &#58&#58 (:id) ', ['id' => $document->number], 'layouts').'<br />'.$e->getMessage());
        }
        // MAIL stuff ENDS


        // Dispatch event
//        $event_class = '\\App\\Events\\'.\Str::singular($this->getParentClass()).'Emailed';
//        event( new $event_class( $document ) );
        

        return redirect()->back()->with('success', l('Your Document has been sent! &#58&#58 (:id) ', ['id' => $document->number], 'layouts'));
    }



/* ********************************************************************************************* */    


}

