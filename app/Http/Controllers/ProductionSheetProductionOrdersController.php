<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProductionSheet;
use App\ProductionOrder;

use App\Configuration;

class ProductionSheetProductionOrdersController extends Controller
{


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
        $work_center_id = $request->input('work_center_id', '');
        $category_id    = $request->input('category_id',    '');

        $work_centerList = \App\WorkCenter::pluck('name', 'id')->toArray();

        // Category Tree
        if ( \App\Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') ) {
            $tree = [];
            $categories =  \App\Category::where('parent_id', '=', '0')->with('children')->orderby('name', 'asc')->get();
            
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
            
            $categoryList =  \App\Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray();
        }

// \DB::enableQueryLog();

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
        return view('production_sheet_production_orders.index', compact('sheet', 'work_centerList', 'categoryList'));
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
     * Create Shipping Slips for a Production Sheet (after Production Sheet Customer Orders).
     * Prepare data for processCreateShippingSlips()
     *
     */
    public function finishProductionOrders( Request $request )
    {
        // ProductionSheetsController
        $document_group = $request->input('document_group', []);

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
        $params = ['production_sheet_id' => $request->production_sheet_id];

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
                                ->where('status', '<>', 'finished')
                                ->with('lines')
    //                            ->orderBy('document_date', 'asc')
    //                            ->orderBy('id', 'asc')
                                ->findOrFail( $list );

            // Check document->status == onhold ???
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->back()
                    ->with('error', l('Some records in the list [ :id ] do not exist', ['id' => implode(', ', $list)], 'layouts'));
            
        }


//        3a.- Pre-process Orders

        foreach ($documents as $document)
        {
            # code...
        }


//        3b.- Group Orders

        $success =[];

        // Do something at last...
        $extra_params = [
            // 
        ];

        foreach ($documents as $document) {
            # code...
            $document->finish();

            $success[] = l('This record has been successfully created &#58&#58 (:id) ', ['id' => $document->id], 'layouts');

            // Lazy debugger lines. Should delete these:
            $document->status = 'released';
            $document->save();
        }

        // die();

        return redirect()
                ->route('productionsheet.productionorders', $params['production_sheet_id'])
                ->with('success', $success);

    }

}
