<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lot;

use Excel;

use App\Traits\DateFormFormatterTrait;

class LotsController extends Controller
{
   
   use DateFormFormatterTrait;

   protected $lot;

   public function __construct(Lot $lot)
   {
        $this->lot = $lot;
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lot  $lot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lot $lot)
    {
        //
    }



    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        abi_r($request->all(), true);       // To do method!!!

        $mvts = $this->stockmovement
                                ->filter( $request->all() )
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
}
