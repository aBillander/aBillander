<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Context;
use App\Models\Lot;
use App\Models\MeasureUnit;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Traits\DateFormFormatterTrait;
use App\Traits\ModelAttachmentControllerTrait;
use Excel;
use Illuminate\Http\Request;

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

        $lots = $lots->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );
        // $lots = $lots->paginate( 1 );

        $lots->setPath('lots');     // Customize the URI used by the paginator

        $warehouseList = Warehouse::selectorList();

        $weight_unit = MeasureUnit::where('id', Configuration::getInt('DEF_WEIGHT_UNIT'))->first();

        $quantity_prefixList = abi_quantity_prefixes();

        return view('lots.index')->with(compact('lots', 'warehouseList', 'weight_unit', 'quantity_prefixList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lots.create');

/*
        echo '<br>You naughty, naughty! Nothing to do here right now. <br><br><a href="'.route('lots.index').'">
                                 Volver a Lotes
                            </a>';
        die();
*/
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

        // Keep it simple, Forget this option:
        if (0)
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

        // Keep it simple, Forget this option:
        if ( 0 && $use_current_stock )
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
                    'currency_id' => Context::getContext()->company->currency->id,
                    'conversion_rate' => Context::getContext()->company->currency->conversion_rate,

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
                    'currency_id' => Context::getContext()->company->currency->id,
                    'conversion_rate' => Context::getContext()->company->currency->conversion_rate,

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
            $stockmovement->update(['lot_quantity_after_movement' => $lot->quantity]);
            $lot->update(['blocked' => 0]);
        }

        return redirect('lots')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $lot->id], 'layouts') . $request->input('reference'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function show(Lot $lot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function edit(Lot $lot)
    {
        // Load Relation
        $lot = $lot->load('product');
        
        // Dates (cuen)
        $this->addFormDates( ['manufactured_at', 'expiry_at'], $lot );

        $warehouseList = Warehouse::selectorList();


        return view('lots.edit', compact('lot', 'warehouseList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lot  $lot
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

        return redirect()->route('lots.edit', $lot->id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $lot->id], 'layouts') . $request->input('reference'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity(Request $request, Lot $lot)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['adjustment_date'], $request );

        $rules = [
            'adjustment_quantity'         => 'sometimes|nullable|numeric|min:0',        // Empty means 0.0
            'adjustment_date'  => 'date',
        ];

        $this->validate($request, $rules);

        $new_lot_quantity = (float) $request->input('adjustment_quantity');
        $adjustment_date  = $request->input('adjustment_date');
        $adjustment_notes = $request->input('adjustment_notes');

        // Product
        $lot->load('product');
        $product = $lot->product;

        // Warehouse
        $warehouse_id = $lot->warehouse_id;

        // Stock in this warehouse
        $stock = $product->getStockByWarehouse( $warehouse_id );

        // New stock
        $new_stock = $stock - $lot->quantity + $new_lot_quantity;


        // abi_r($stock);
        // abi_r($new_stock);die();


        // Magic here:

        // 1.- Create Stock Movement type 12 (Stock adjustment)
            $data = [
                    'date' => $adjustment_date,

//                    'stockmovementable_id' => $this->,
//                    'stockmovementable_type' => $this->,

//                    'document_reference' => $this->document_reference,

//                    'quantity_before_movement' => $this->,
                    'quantity' => $new_stock,
                    'measure_unit_id' => $product->measure_unit_id,
//                    'quantity_after_movement' => $this->,

                    'price' => $product->cost_price,
                    'price_currency' => $product->cost_price,
//                    'currency_id' => $this->currency_id,
//                    'conversion_rate' => $this->currency_conversion_rate,

                    'notes' => $adjustment_notes,

                    'product_id' => $product->id,
 //                   'combination_id' => $this->combination_id,
                    'reference' => $product->reference,
                    'name' => $product->name,

                    'warehouse_id' => $warehouse_id,
//                    'warehouse_counterpart_id' => $this->,

                    'movement_type_id' => StockMovement::ADJUSTMENT,

                    'user_id' => \Auth::id(),

//                    'inventorycode'
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                // $this->stockmovements()->save( $stockmovement );

                if ($lot)
                {
                    $lot->stockmovements()->save( $stockmovement );
                    $stockmovement->update(['lot_quantity_after_movement' => $new_lot_quantity]);
                    $lot->update(['blocked' => 0]);
                }
            }


        // 2.- Link Stock movement to Lot


        

        // 3.- Update Lot
        $lot->update( ['quantity' => $new_lot_quantity] );

        return redirect()->route('lot.stockmovements', $lot->id)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $lot->id], 'layouts') . $request->input('reference'));
    }



    public function split(Request $request, Lot $lot)
    {
        // 

        $rules = [
            'lot_reference'         => 'required|min:2|max:32',
            'quantity'   => 'required|numeric|not_in:0',
            'warehouse_id'     => 'exists:warehouses,id',
        ];

        $this->validate($request, $rules);

        $lot_reference = $request->input('lot_reference');
        $lot_quantity = $request->input('quantity');
        $lot_warehouse_id = $request->input('warehouse_id');

        $product = $this->product->find( $lot->product_id );

        // STEP 1
        // Stock adjustment for the Original Lot:
        // Substract $lot_quantity

        // New Quantity
        $new_lot_quantity = $lot->quantity - $lot_quantity;

        // Stock in this warehouse
        $stock = $product->getStockByWarehouse( $lot->warehouse_id );

        // New stock
//        $new_stock = $stock - $lot->quantity + $lot_quantity;
        $new_stock = $stock - $lot_quantity;

        // Magic here:

        $movement_type_id = StockMovement::ADJUSTMENT;

        // 1.- Create Stock Movement type 12 (Stock adjustment)
            $data = [

                'movement_type_id' => $movement_type_id,
                'date' => \Carbon\Carbon::now(),

//                    'stockmovementable_id' => $this->,
//                    'stockmovementable_type' => $this->,

                    'document_reference' => l('New Adjustment by Lot (:id) ', ['id' => $lot->id], 'lots').$lot->reference,

//                    'quantity_before_movement' => $this->,
                    'quantity' => $new_stock,
                    'measure_unit_id' => $product->measure_unit_id,
//                    'quantity_after_movement' => $this->,

                    'price' => $product->getPriceForStockValuation(),
                    'price_currency' => $product->cost_price,
//                    'currency_id' => $this->currency_id,
//                    'conversion_rate' => $this->currency_conversion_rate,

                    'notes' => '',

                    'product_id' => $product->id,
 //                   'combination_id' => $this->combination_id,
                    'reference' => $product->reference,
                    'name' => $product->name,

                    'warehouse_id' => $lot->warehouse_id,
//                    'warehouse_counterpart_id' => $this->,
            ];

            $stockmovement = StockMovement::createAndProcess( $data );

            if ( $stockmovement )
            {
                //
                // $this->stockmovements()->save( $stockmovement );

                if ($lot)
                {
                    $lot->stockmovements()->save( $stockmovement );
                    $stockmovement->update(['lot_quantity_after_movement' => $new_lot_quantity]);
                    $lot->update(['blocked' => 0]);
                }
            }
        
        // 3.- Update Lot
        $lot->update( ['quantity' => $new_lot_quantity] );





        // STEP 2
        // Create new Lot with quantity = $lot_quantity
        // Get back $lot_quantity

        $lot_params = [
            'reference' => $lot_reference,
            'product_id' => $lot->product_id, 
//            'combination_id' => ,
            'quantity_initial' => $lot_quantity, 
            'quantity' => $lot_quantity, 
            'measure_unit_id' => $lot->measure_unit_id, 
//            'package_measure_unit_id' => , 
//            'pmu_conversion_rate' => ,
            'manufactured_at' => $lot->manufactured_at, 
            'expiry_at' => $lot->expiry_at,
            'notes' => '',

            'warehouse_id' => $lot_warehouse_id,
        ];

        $new_lot = $this->lot->create( $lot_params );

        // Let's play a little bit with Stocks, now!
        // (ノಠ益ಠ)ノ彡┻━┻
        // New Lot is a Stock Adjustment (lot quantity "increases" overall stock)

        $movement_type_id = StockMovement::ADJUSTMENT;

        // Let's move on:
        $data = [

                'movement_type_id' => $movement_type_id,
                'date' => \Carbon\Carbon::now(),

//                   'stockmovementable_id' => ,
//                   'stockmovementable_type' => ,

                'document_reference' => l('New Adjustment by Lot (:id) ', ['id' => $new_lot->id], 'lots').$new_lot->reference,
//                   'quantity_before_movement' => ,
                'quantity' => $new_lot->quantity_initial + $product->getStockByWarehouse( $new_lot->warehouse_id ),
                'measure_unit_id' => $product->measure_unit_id,
//                   'quantity_after_movement' => ,

                'price' => $product->getPriceForStockValuation(),
                'currency_id' => Context::getContext()->company->currency->id,
                'conversion_rate' => Context::getContext()->company->currency->conversion_rate,

                'notes' => '',

                'product_id' => $product->id,
                'combination_id' => '', // $line->combination_id,
                'reference' => $product->reference,
                'name' => $product->name,

//                'lot_id' => $lot->id,

                'warehouse_id' => $new_lot->warehouse_id,
//                   'warehouse_counterpart_id' => ,
                
        ];

        $stockmovement = StockMovement::createAndProcess( $data );

        $new_lot->stockmovements()->save( $stockmovement );
        $stockmovement->update(['lot_quantity_after_movement' => $new_lot->quantity]);
        $new_lot->update(['blocked' => 0]);



        return redirect()->route('lots.edit', $new_lot->id)
                    ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $new_lot->id], 'layouts'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lot  $lot
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
    public function exportMovements(Lot $lot, Request $request)
    {
        // See: HelferinCustomerInvoicesTrait

        $sections = 'all';
        if ( $request->has('movements') )
            $sections = 'movements';
        if ( $request->has('allocations') )
            $sections = 'allocations';

        // Load Relations
        $lot = $lot->load(['product', 'measureunit', 'stockmovements', 'lotallocateditems', 'lotallocateditems.lotable']);

        $stockmovements = $lot->stockmovements
                                ->sortByDesc('date')
                                ->sortByDesc('created_at');

        $stockallocations = $lot->lotallocateditems;


        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $ribbon  = " :: " . $lot->reference . " [".optional($lot->measureunit)->sign."]";
        $ribbon1 = " [".$lot->product->reference."] " . $lot->product->name;


        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = [l('Lot Stock Movements', 'lots') . $ribbon, '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [$ribbon1];
        $data[] = [''];
        $data[] = [l('Initial Stock', 'lots'), l('Current Stock', 'lots'), l('Allocated Stock', 'lots'), l('Available Stock', 'lots'), ''];
        $data[] = [ (float) $lot->measureunit->quantityable($lot->quantity_initial), 
                    (float) $lot->measureunit->quantityable($lot->quantity), 
                    (float) $lot->measureunit->quantityable($lot->allocatedQuantity()), 
                    (float) $lot->measureunit->quantityable($lot->quantity - $lot->allocatedQuantity()) ];
        $data[] = [''];

if ( $sections != 'allocations' )
{
        // Define the Excel spreadsheet headers
        $header_names = [
                l('ID', [], 'layouts'),
                l('Date', 'lots'),
                l('Type', 'lots'),
                l('Warehouse', 'lots'),
                l('Quantity', 'lots'),
                l('Stock after', 'lots'),
                l('Document', 'lots'),
                l('Notes', [], 'layouts')
        ];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.

//        $totat_quantity = $total_weight = 0.0;

        foreach ($stockmovements as $stockmovement) 
        {
            $row = [];
            $row[] = $stockmovement->id;
            $row[] = abi_date_short( $stockmovement->date );
            $row[] = '['.$stockmovement->movement_type_id.'] - '. StockMovement::getTypeName($stockmovement->movement_type_id);
            $row[] = $stockmovement->warehouse->alias;
            $row[] = (float) $stockmovement->as_quantityable( $stockmovement->quantity_after_movement - $stockmovement->quantity_before_movement );
            $row[] = (float) $stockmovement->as_quantity( 'lot_quantity_after_movement' );
            $row[] = $stockmovement->document_reference;
            $row[] = $stockmovement->notes;

            $data[] = $row;

//            $totat_quantity += $lot->quantity;
//            $total_weight   += $lot->getWeight();
        }

        $data[] = [];

//        $data[] = ['', '', '', 'TOTAL:', $totat_quantity, '', 'TOTAL:', $total_weight, '', '', '', ''];
}

        $lines_so_far = count($data);

if ( $sections != 'movements' )
{
        //
        // Bonus:
        // Allocations
        $data[] = [l('Lot Stock Allocations', 'lots') . $ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = [
                l('ID', [], 'layouts'),
                l('Document Date', 'lots'),
                l('Document', 'lots'),
                l('Customer', 'lots'),
                l('Quantity', 'lots'),
        ];

        $data[] = $header_names;

        foreach ($stockallocations as $stockallocation) 
        {
            $document = optional($stockallocation->lotable)->document;

            $row = [];
            $row[] = $stockallocation->id;
            $row[] = abi_date_short( $document->document_date );
            $row[] = optional(optional($stockallocation->lotable)->document)->document_reference;
                $customer = '';
                if ( $document->customer_id )
                    $customer = $document->customer->name_commercial;
                else if( $document->warehouse_id )
                    $customer = optional($document->warehouse)->getAliasNameAttribute();
            $row[] = $customer;
            $row[] = (float) $lot->measureunit->quantityable( $stockallocation->quantity );

            $data[] = $row;

//            $totat_quantity += $lot->quantity;
//            $total_weight   += $lot->getWeight();
        }

        $data[] = [];
}



        $sheetName = 'Movimientos' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create($lot->reference.' - '.l('Lot Stock Movements', 'lots'), function($excel) use ($sheetName, $data, $lines_so_far) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data, $lines_so_far) {
                
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');

                $nbr = $lines_so_far + 1;
                $sheet->mergeCells("A$nbr:D$nbr");

                $sheet->getStyle('A2:F2')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->getStyle('A5:L5')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->getStyle('A8:L8')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $nbr = $lines_so_far + 1;

                $sheet->getStyle("A$nbr:L$nbr")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $nbr = $lines_so_far + 3;

                $sheet->getStyle("A$nbr:L$nbr")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $nbr = count($data);

                $sheet->getStyle("A$nbr:L$nbr")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
//                    'C' => '0.00',
//                    'H' => '0.00',

                ));

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs
    }



    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function exportAllocations(Lot $lot, Request $request)
    {
        // See: HelferinCustomerInvoicesTrait

        // Load Relation
        $lot = $lot->load(['product', 'measureunit']);
    }



    public function stockmovements(Lot $lot, Request $request)
    {
        // Load Relation
        $lot = $lot->load(['product', 'measureunit']);

        $stockmovements = $this->stockmovement
                                ->where('lot_id', $lot->id)
 //                               ->filter( $request->all() )
 //                               ->with('measureunit')
                                ->orderBy('date', 'DESC')
                                ->orderBy('created_at', 'DESC');

        $stockmovements = $stockmovements->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );
        // $lots = $lots->paginate( 1 );

        $stockmovements->setPath('stockmovements');     // Customize the URI used by the paginator
        
        // Dates (cuen)
        // $this->addFormDates( ['manufactured_at', 'expiry_at'], $lot );


        // More stuff
        $stockallocations = $lot->lotallocateditems()->with('lotable')->get();


        return view('lots.lot_stock_movements', compact('lot', 'stockmovements', 'stockallocations'));
    }

}
