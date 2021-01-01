<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;
use App\Lot;
use App\Product;
use App\StockMovement;

use Excel;

use App\Traits\DateFormFormatterTrait;
use App\Traits\ModelAttachmentControllerTrait;

class LotsController extends Controller
{
   
   use DateFormFormatterTrait;
   use ModelAttachmentControllerTrait;

   protected $lot;
   protected $product;

   public function __construct(Lot $lot, Product $product, StockMovement $stockmovement)
   {
        $this->lot = $lot;
        $this->product = $product;
        $this->stockmovement = $stockmovement;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

//        abi_r($request->all(), true);

        $lots = $this->lot
                                ->filter( $request->all() )
                                ->with('product')
                                ->with('combination')
                                ->with('measureunit')
                                ->orderBy('created_at', 'DESC');

//         abi_r($lots->toSql(), true);

        $lots = $lots->paginate( \App\Configuration::get('DEF_ITEMS_PERPAGE') );
        // $lots = $lots->paginate( 1 );

        $lots->setPath('lots');     // Customize the URI used by the paginator

        $warehouseList = \App\Warehouse::selectorList();

        return view('lots.index')->with(compact('lots', 'warehouseList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lots.create');

        echo '<br>You naughty, naughty! Nothing to do here right now. <br><br><a href="'.route('lots.index').'">
                                 Volver a Lotes
                            </a>';
        die();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['manufactured_at', 'expiry_at'], $request );

        // abi_r($request->all(), true);

        $this->validate($request, Lot::$rules);

        $use_current_stock = $request->input('use_current_stock', 0);

        $product = $this->product->find( $request->input('product_id') );

        $warehouse_id = $request->input('warehouse_id');

        if ( $use_current_stock )
        {
            // Check unallocated quantity
            $unallocated = $product->getStockByWarehouse( $warehouse_id ) - $product->getLotStockByWarehouse( $warehouse_id );
            
            if ( $unallocated < $request->input('quantity') )
            {
                // return with error
                return redirect('lots')
                        ->with('error', l('No se pudo crear el Lote porque no hay suficiente Stock. Stock sin asignar a Lotes: :u. Stock pedido para el Lote: :q.', ['u' => $unallocated, 'q' => $request->input('quantity')]));
            }

        }

        $lot = $this->lot->create($request->all() + ['quantity_initial' => $request->input('quantity')]);

        // Let's play a little bit with Stocks, now!
        // (ノಠ益ಠ)ノ彡┻━┻
        // New Lot is a Stock Adjustment (lot quantity "increases" overall stock)

        if ( $use_current_stock )
        {
            // Stock Transfer inside Warehouse            

            // Prepare StockMovement::TRANSFER_OUT
            $data = [

                    'movement_type_id' => StockMovement::TRANSFER_OUT,
                    'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $line->,
//                    'stockmovementable_type' => $line->,

                    'document_reference' => l('New Adjustment by Lot (:id) ', ['id' => $lot->id], 'lots').$lot->reference,

//                    'quantity_before_movement' => $line->,
                    'quantity' => $lot->quantity_initial,
                    'measure_unit_id' => $product->measure_unit_id,
//                    'quantity_after_movement' => $line->,

                    'price' => $product->getPriceForStockValuation(),
                    'currency_id' => \App\Context::getContext()->company->currency->id,
                    'conversion_rate' => \App\Context::getContext()->company->currency->conversion_rate,

                    'notes' => '',

                    'product_id' => $product->id,
                    'combination_id' => '', // $line->combination_id,
                    'reference' => $product->reference,
                    'name' => $product->name,

    //                'lot_id' => $lot->id,

                    'warehouse_id' => $lot->warehouse_id,
                    'warehouse_counterpart_id' => $lot->warehouse_id,

            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                // $line->stockmovements()->save( $stockmovement );
            }


            // The show **MUST** go on

            // Prepare StockMovement::TRANSFER_IN
            $data1 = [
                    'warehouse_id' => $lot->warehouse_id,
                    'warehouse_counterpart_id' => $lot->warehouse_id,

                    'movement_type_id' => StockMovement::TRANSFER_IN,
            ];

            $stockmovement = StockMovement::createAndProcess( array_merge($data, $data1) );

            if ( $stockmovement )
            {
                //
                $lot->stockmovements()->save( $stockmovement );
            }


        } else {
        
            // $movement_type_id = StockMovement::INITIAL_STOCK;
            $movement_type_id = StockMovement::ADJUSTMENT;

            // Let's move on:
            $data = [

                    'movement_type_id' => $movement_type_id,
                    'date' => \Carbon\Carbon::now(),

    //                   'stockmovementable_id' => ,
    //                   'stockmovementable_type' => ,

                    'document_reference' => l('New Adjustment by Lot (:id) ', ['id' => $lot->id], 'lots').$lot->reference,
    //                   'quantity_before_movement' => ,
                    'quantity' => $lot->quantity_initial + $product->getStockByWarehouse( $lot->warehouse_id ),
                    'measure_unit_id' => $product->measure_unit_id,
    //                   'quantity_after_movement' => ,

                    'price' => $product->getPriceForStockValuation(),
                    'currency_id' => \App\Context::getContext()->company->currency->id,
                    'conversion_rate' => \App\Context::getContext()->company->currency->conversion_rate,

                    'notes' => '',

                    'product_id' => $product->id,
                    'combination_id' => '', // $line->combination_id,
                    'reference' => $product->reference,
                    'name' => $product->name,

    //                'lot_id' => $lot->id,

                    'warehouse_id' => $lot->warehouse_id,
    //                   'warehouse_counterpart_id' => ,
                    
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            $lot->stockmovements()->save( $stockmovement );
        }

        return redirect('lots')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $lot->id], 'layouts') . $request->input('reference'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function show(Lot $lot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function edit(Lot $lot)
    {
        // Load Relation
        $lot = $lot->load('product');
        
        // Dates (cuen)
        $this->addFormDates( ['manufactured_at', 'expiry_at'], $lot );


        return view('lots.edit', compact('lot'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lot $lot)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['manufactured_at', 'expiry_at'], $request );

        $rules = [
            'reference'         => 'required|min:2|max:32',
            'manufactured_at' => 'nullable|date',
            'expiry_at'  => 'nullable|date',
        ];

        $this->validate($request, $rules);

        $lot->update($request->only(['reference', 'manufactured_at', 'expiry_at', 'blocked', 'notes']));

        return redirect('lots')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $lot->id], 'layouts') . $request->input('reference'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lot $lot)
    {
        $id = $lot->id;
        $reference = $lot->reference;

        $lot->delete();

        return redirect('lots')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts').$reference);
    }



    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export(Lot $lot, Request $request)
    {
        // See: StockMovementsController

        // Load Relation
        $lot = $lot->load(['product', 'measureunit']);

        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

//        abi_r($request->all(), true);       // To do method!!!

        $mvts = $this->stockmovement
                                ->filter( $request->all() )
                                ->where('lot_id', $lot->id)
//                              ->with('warehouse')
//                              ->with('product')
//                              ->with('combination')
//                              ->with('stockmovementable')
//                              ->with('stockmovementable.document')
                                ->orderBy('date', 'DESC')
                                ->orderBy('id', 'DESC')
                          ->get();

        // Limit number of records
        if ( ($count=$mvts->count()) > 1000 )
            return redirect()->back()
                    ->with('error', l('Too many Records for this Query &#58&#58 (:id) ', ['id' => $count], 'layouts'));

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $headers = [ 
                    'id', 'date', 'stockmovementable_id', 'stockmovementable_type', 'document_reference', 

                    'quantity_before_movement', 'quantity', 'measure_unit_id', 'quantity_after_movement', 

                    'cost_price_before_movement', 'cost_price_after_movement', 'price', 'price_currency', 'currency_id', 'conversion_rate', 

                    'notes', 'product_id', 'combination_id', 'reference', 'name', 

                    'warehouse_id', 'warehouse_counterpart_id', 'movement_type_id', 'MOVEMENT_TYPE_NAME', 'user_id', 'inventorycode', 'created_at', 'updated_at', 'deleted_at',
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($mvts as $mvt) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $mvt->{$header} ?? '';
            }
//            $row['TAX_NAME']          = $category->tax ? $category->tax->name : '';

            $row['MOVEMENT_TYPE_NAME'] = StockMovement::getTypeName($mvt->movement_type_id);

            $data[] = $row;
        }

        $sheetName = 'Stock Movements' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Stock_Movements', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs
    }



    public function stockmovements(Lot $lot, Request $request)
    {
        // Load Relation
        $lot = $lot->load(['product', 'measureunit']);

        $stockmovements = $this->stockmovement
                                ->where('lot_id', $lot->id)
 //                               ->filter( $request->all() )
 //                               ->with('measureunit')
                                ->orderBy('date', 'ASC')
                                ->orderBy('created_at', 'ASC');

        $stockmovements = $stockmovements->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );
        // $lots = $lots->paginate( 1 );

        $stockmovements->setPath('stockmovements');     // Customize the URI used by the paginator
        
        // Dates (cuen)
        // $this->addFormDates( ['manufactured_at', 'expiry_at'], $lot );


        return view('lots.lot_stock_movements', compact('lot', 'stockmovements'));
    }

}
