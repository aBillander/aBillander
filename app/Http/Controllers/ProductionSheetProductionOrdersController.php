<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Lot;
use App\Models\LotItem;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\ProductionSheet;
use App\Models\Warehouse;
use App\Models\WorkCenter;
use App\Traits\DateFormFormatterTrait;
use Illuminate\Http\Request;

class ProductionSheetProductionOrdersController extends Controller
{

   use DateFormFormatterTrait;

   protected $productionSheet;
   protected $productionOrder;

   public function __construct(ProductionSheet $productionSheet, ProductionOrder $productionOrder)
   {
        $this->productionSheet = $productionSheet;
        $this->productionOrder = $productionOrder;
   }
   
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productionordersIndex($id, Request $request)
    {
        // $p = $this->productionOrder->find(13461);

        // abi_r($p->lots);die();

        $work_center_id = $request->input('work_center_id', '');
        $category_id    = $request->input('category_id',    '');

        $work_centerList = WorkCenter::pluck('name', 'id')->toArray();

        // Category Tree
        if ( Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') ) {
            $tree = [];
            $categories =  Category::where('parent_id', '=', '0')->with('children')->orderby('name', 'asc')->get();
            
            foreach($categories as $category) {
                $label = $category->name;

                // Prevent duplicate names
                while ( array_key_exists($label, $tree))
                    $label .= ' ';

                $tree[$label] = $category->children()->orderby('name', 'asc')->pluck('name', 'id')->toArray();
                // foreach($category->children as $child) {
                    // $tree[$category->name][$child->id] = $child->name;
                // }
            }

            $categoryList = $tree;

        } else {
            
            $categoryList =  Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray();
        }

        $warehouseList = Warehouse::selectorList();

// \DB::enableQueryLog();

        // Custom colletion sort order
        // https://stackoverflow.com/questions/40731863/sort-collection-by-custom-order-in-eloquent

        $sheet = $this->productionSheet
                        ->with(['productionorders' => function($query) use ($work_center_id, $category_id) {

                                    $query->when($work_center_id, function($query1) use ($work_center_id) {
                 
                                             $query1->where('work_center_id', $work_center_id);
                                    });
                                    $query->when($category_id,    function($query1) use ($category_id) {
                 
                                             $query1->where('schedule_sort_order', $category_id);
                                    });

                                    $query->with('product');
                          }])
//                        ->with('productionorders')
//                        ->with('productionorders.product')
                        ->findOrFail($id);

        // https://www.itsolutionstuff.com/post/how-to-get-last-executed-query-in-larave-5example.html

        // DB::enableQueryLog();

        // $user = User::get();

 //       $query = \DB::getQueryLog();

        // $query = end($query);

        // abi_r($query); die();

/* * /
        abi_toSql($this->productionSheet
                        ->whereHas('productionorders', function($query) use ($work_center_id, $category_id) {

                                    $query->when($work_center_id, function($query1) use ($work_center_id) {
                 
                                             $query1->where('work_center_id', $work_center_id);
                                    });
                                    $query->when($category_id,    function($query1) use ($category_id) {
                 
                                             $query1->where('schedule_sort_order', $category_id);
                                    });
                          })
                        ->with('productionorders')
                        ->with('productionorders.product')
                        ); // die();
/ * */
        return view('production_sheet_production_orders.index', compact('sheet', 'work_centerList', 'categoryList', 'warehouseList'));
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


    /**
     *
     * Let' rock >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.
     *
     */


    /**
     * 
     *
     */
    // Deprecated; keep for reference
    public function finishWithLot(Request $request )
    {
/*
        // Dates (cuen)
        $this->mergeFormDates( ['finish_date'], $request );

        $production_sheet_id = $request->input('production_sheet_id');
        $production_order_id = $request->input('finish_production_order_id');

        // abi_r($request->All());die();

        // Do the Mambo!
        $document = $this->productionOrder->with('product')->findOrFail($production_order_id);

        $lot_tracking = $document->product->lot_tracking;

        $rules = [
            // To Do!
          //      'country_id' => 'exists:countries,id',
         //     'percent' => array('required', 'numeric', 'between:0,100')
        ];

        if ( $lot_tracking )
        {
            $rules = $rules + [
//                'lot_reference'    => 'required|min:2|max:32',    // <= Lot number is calculated according to finish date (manufacturing date)
                'warehouse_id'     => 'exists:warehouses,id',
            ];
        }
    
        $this->validate($request, $rules);

        $finished_quantity = $request->input('quantity');
//        $lot_reference = $request->input('lot_reference', '');
        $finish_date = $request->input('finish_date');
        $expiry_time = $request->input('expiry_time', $document->product->expiry_time);

        $warehouse_id = $request->input('warehouse_id');

        // Sorry, force lot number according to product data
        $lot_reference = Lot::generate( $finish_date, $document->product, $document->product->expiry_time);

        // abi_r('OK');die();

        $params = [
            'production_sheet_id' => $production_sheet_id,
            'production_order_id' => $production_order_id,
            'finished_quantity'   => $finished_quantity, 

            'finish_date'       => $finish_date,
            'warehouse_id'      => $warehouse_id,

            'lot_tracking'      => $lot_tracking,
        ];

        // abi_r($params, true);

        // return $this->processFinishProductionOrders( $document_group, $params );

        $lot_params = [
            'reference' => $lot_reference,
            'product_id' => $document->product_id, 
//            'combination_id' => ,
            'quantity_initial' => $finished_quantity, 
            'quantity' => $finished_quantity, 
            'measure_unit_id' => $document->product->measure_unit_id, 
//            'package_measure_unit_id' => , 
//            'pmu_conversion_rate' => ,
            'manufactured_at' => $finish_date, 
//            'expiry_at' => \Carbon\Carbon::createFromFormat('Y-m-d', $finish_date)->addDays( $expiry_time ),
            'expiry_at' => Lot::getExpiryDate( $finish_date, $expiry_time ),
            'notes' => 'Production Order: #'.$document->id,

            'warehouse_id'      => $warehouse_id,
        ];

        // abi_r($lot_params, true);

        $params['lot_params'] = $lot_params;

        $message = 'success';
        $success =[];

        if ( $document->finish( $params ) )
        {
            $success[] = l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $document->id], 'layouts');

            if ( $lot_tracking )       // Moved to ProductionOrderFinishedListener ( $document->makeStockMovements( $params ); )
            {
                // Create Lot
                // $lot = Lot::create($lot_params);
    
                // $lot_item = LotItem::create(['lot_id' => $lot->id]);
    
                // $document->lotitems()->save($lot_item);
    
                $success[] = l('Se ha creado un lote &#58&#58 (:id) ', ['id' => $lot_reference], 'layouts') . 
                    'para el Producto: ['.$document->product_reference.'] '.$document->product_name;
            }
        } 
        else {

            $message = 'error';
            $success[] = 'No se ha podido cerrar la Orden de Fabricación.';
        }


        return redirect()
                ->route('productionsheet.productionorders', $production_sheet_id)
                ->with($message, $success);
*/
    }


    /**
     *  Maybe this function is obsolete ????
     *
     */
    public function finish(Request $request )
    {
        // Dates (cuen)
        $this->mergeFormDates( ['finish_date'], $request );

        $production_sheet_id = $request->input('production_sheet_id');
        $production_order_id = $request->input('finish_production_order_id');

        $document = $this->productionOrder->with('product')->findOrFail($production_order_id);

        $document_group = [$document->id];

        $finished_quantity = $request->input('quantity');
        
        $finish_date  = $request->input('finish_date');
        $warehouse_id = $request->input('warehouse_id');

        $params = [
            'production_sheet_id' => $document->production_sheet_id, 
            'document_group' => $document_group,

            'finished_quantity'   => $finished_quantity, 

            'finish_date'  => $finish_date,
            'warehouse_id' => $warehouse_id
        ];

        // abi_r($params, true);

        return $this->processFinishProductionOrders( $document_group, $params );
    }

    
    public function finishBulk( Request $request )
    {
        // Dates (cuen)
        $this->mergeFormDates( ['orders_finish_date'], $request );

        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

        $finish_date  = $request->input('orders_finish_date');
        $warehouse_id = $request->input('orders_warehouse_id');

        if ( count( $document_group ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $request->production_sheet_id], 'layouts'));
        
        // abi_r('You naughty, naughty!');
        // abi_r( $request->all() , true);
/*
        // Dates (cuen)
        $this->mergeFormDates( ['document_date', 'delivery_date'], $request );
        $request->merge( ['shippingslip_date' => $request->input('document_date')] );   // According to $rules_createshippingslip
        
        $request->merge( ['shippingslip_delivery_date' => $request->input('delivery_date')] );   // According to $rules_createshippingslip

        $rules = $this->document::$rules_createshippingslip;

        $this->validate($request, $rules);

        // abi_r($request->all(), true);

        // Set params for group
        $params = $request->only('production_sheet_id', 'should_group', 'template_id', 'sequence_id', 'document_date', 'delivery_date', 'status');
*/
        $params = [
            'production_sheet_id' => $request->production_sheet_id, 
            'document_group' => $document_group,

//            'finished_quantity' => $request->input('quantity'),   // <= finished quantity is order quantity here

            'finish_date'  => $finish_date,
            'warehouse_id' => $warehouse_id
        ];

        // abi_r($params, true);

        return $this->processFinishProductionOrders( $document_group, $params );
    }

    
    /**
     * Create Shipping Slips after a list of Customer Orders (id's).
     * Hard work is done here
     *
     */
    public function processFinishProductionOrders( $list, $params )
    {

//        1.- Recuperar los documntos
//        2.- Comprobar que están todos los de la lista ( comparando count() )

        try {

            $productionSheet = $this->productionSheet
                                ->findOrFail($params['production_sheet_id']);

            $documents = $this->productionOrder
                                ->where('production_sheet_id', $params['production_sheet_id'])
                                ->whereIn('id', $params['document_group'])
                                ->where('status', '<>', 'finished')
                                ->with('product')
                                ->with('lines')
    //                            ->orderBy('document_date', 'asc')
    //                            ->orderBy('id', 'asc')
    //                            ->findOrFail( $list );
                                ->find( $list );

            // Check document->status == onhold ???
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }


//        3a.- Pre-process Orders

        foreach ($documents as $document)
        {
            # code...
            // abi_r($document->id);
        }


//        3b.- Group Orders

        $success =[];

        // Do something at last...
        $extra_params = [
            // 
        ];

        foreach ($documents as $document) {
            # code...
            // Skip lot controlled orders
            if ( 0 && $document->product->lot_tracking )
            {
                //
                $success[] = '<strong>ERROR:</strong> Debe dar un Número de Lote &#58&#58 ('.$document->id.')';

                continue;
            }

            $document->finish( $params );

            $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts');
        }

        // die();

        return redirect()
                ->route('productionsheet.productionorders', $params['production_sheet_id'])
                ->with('success', $success);

    }



/* ********************************************************************************************* */  


    /**
     * AJAX Stuff.
     *
     * 
     */

    /**
     * Get lot reference for product & manufacturing date
     *
     */
    public function getLotReference( Request $request )
    {
        // return 'ok';

        // Dates (cuen)
        $this->mergeFormDates( ['finish_date'], $request );

        $finish_date  = $request->input('finish_date');

        $product = Product::find( $request->input('product_id', 0) );

        $lot_reference = $product ? Lot::generate( $finish_date, $product, $product->expiry_time) : '';


        return json_encode( [ 'lot_reference' => $lot_reference, 'request' => $request->toArray() ] );

    }

}
