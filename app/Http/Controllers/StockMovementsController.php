<?php

namespace App\Http\Controllers;

use App\Exceptions\StockMovementException;
use App\Helpers\Exports\ArrayExport;
use App\Models\Combination;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Currency;
use App\Models\Product;
use App\Models\StockMovement;
use App\Traits\DateFormFormatterTrait;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use View, Cookie;

class StockMovementsController extends Controller 
{
   
   use DateFormFormatterTrait;

   protected $stockmovement;
   protected $product;

   public function __construct(StockMovement $stockmovement, Product $product)
   {
        $this->stockmovement = $stockmovement;
        $this->product = $product;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

//        abi_r($request->all(), true);

        $mvts = $this->stockmovement
        						->filter( $request->all() )
        						->with('warehouse')
        						->with('product')
        						->with('combination')
//        						->with('stockmovementable')
        						->with('stockmovementable.document')
        						->orderBy('created_at', 'DESC')
        						->orderBy('id', 'DESC');

//         abi_r($mvts->toSql(), true);

        $mvts = $mvts->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );
        // $mvts = $mvts->paginate( 1 );

        $mvts->setPath('stockmovements');     // Customize the URI used by the paginator

        return View::make('stock_movements.index')->with('stockmovements', $mvts);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
//		abi_r(Cookie::get('document_reference'), true);
		$date = Cookie::get('date') ? Cookie::get('date') : abi_date_short( \Carbon\Carbon::now() );
		$time = Cookie::get('time') ? Cookie::get('time') : 'now';
		$document_reference = Cookie::get('document_reference') ? Cookie::get('document_reference') : '';
		$movement_type_id   = Cookie::get('movement_type_id')   ? Cookie::get('movement_type_id')   : '0';
		$currency_id        = Cookie::get('currency_id')        ? Cookie::get('currency_id')        : Context::getContext()->currency->id;

		return View::make('stock_movements.create', compact('date', 'time', 'document_reference', 'movement_type_id', 'currency_id'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// abi_r($request->all(), true);

		// Valid type?
		$this->validate($request, StockMovement::validTypeRule());

		$product_id = $request->input('product_id');

		$product = $this->product->findOrFail($product_id);

		if ( Configuration::isTrue('ENABLE_LOTS') && ($product->lot_tracking > 0 ) )
		{
			//
			return redirect()->back()
				->with('error', l('This Product has "Lot tracking" enabled. ').l('No action is taken &#58&#58 (:id) ', ['id' => $product->name.' - '.$request->input('quantity')], 'layouts') . 
					' [' . $request->input('date') . ']' );
		}


        if ( $request->input('movement_type_id') == 10 ) {
        	// $product = \App\Product::find( $request->input('product_id') );
            if ( $product->getStockByWarehouse( $request->input('warehouse_id') ) > 0.0 )
				return redirect('stockmovements/create')
						->with( 'error', l('Can not set Initial Stock &#58&#58 (:id) :name has already non zero stock in Warehouse :ws', ['id' => $product->id, 'name' => $product->name, 'ws' => $request->input('warehouse_id')]) );
        }

		// Move on
		$action = $request->input('nextAction', '');

		// Has Combination?
		if ($request->has('group')) {
			$combination_id = Combination::getCombinationByOptions( $request->input('product_id'), $request->input('group') );
			$request->merge(array('combination_id' => $combination_id));
		} else {
			$combination_id = null;
		}

		$conversion_rate = $request->input('conversion_rate', 0.0);
		$conversion_rate = $conversion_rate ?: Currency::find($request->input('currency_id'))->conversion_rate;

		$date_raw = $request->input('date');
		$time = $request->input('time');
		// https://styde.net/componente-carbon-fechas-laravel-5/
		// https://es.stackoverflow.com/questions/57020/validaci%C3%B3n-de-formato-de-fecha-no-funciona-laravel-5-3
		$date_view = \Carbon\Carbon::createFromFormat( Context::getContext()->language->date_format_lite, $request->input('date') );

//		abi_r($date_view->toDateTimeString());
//		abi_r($date_view->toDateString(), true);
 
		$data      = ['price' => $request->input('price_currency'),
					  'conversion_rate' => $conversion_rate, 
					  ];

		$request->merge( $data );
 
		$extradata = ['date' =>  $date_view->toDateString() . " $time:00", 
					  'combination_id' => $combination_id, 
//					  'user_id' => \Auth::id()
					  ];

		$rules = StockMovement::getRules( $request->input('movement_type_id') );
		if ( !$combination_id ) unset( $rules['combination_id'] );

//		if ( $request->input('movement_type_id') == 20 )
//			$rules['quantity'][] = new \App\Rules\Decimal();

		$this->validate($request, $rules);	// abi_r($request->all(), true);

        // Product
        if ($combination_id>0) {
            $combination = Combination::with('product')->find(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            // Already set:
            // $product = \App\Product::findOrFail(intval($product_id));
        }

        $extradata['reference']  = $product->reference;
        $extradata['name']       = $product->name;
        $extradata['price']      = $request->input('price_currency') / $conversion_rate;


//		$stockmovement = $this->stockmovement->create( array_merge( $request->all(), $extradata ) );
//		$stockmovement = $this->stockmovement->createAndProcess( array_merge( $request->all(), $extradata ) );
		# $stockmovement = StockMovement::createAndProcess( array_merge( $request->all(), $extradata ) );

        try {
            $stockmovement = StockMovement::createAndProcess( $request->merge( $extradata )->all() );
        } catch (StockMovementException $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }


		// Time savers
		Cookie::queue('date', $date_raw, 1);	// Live 1 minute
		Cookie::queue('time', $time, 1);
		Cookie::queue('document_reference', $request->input('document_reference'), 1);
		Cookie::queue('movement_type_id',   $request->input('movement_type_id'), 1);
		Cookie::queue('currency_id',        $request->input('currency_id'), 1);

		// Stock movement fulfillment (perform stock movements)
//		$stockmovement->process();

        if ($action == 'saveAndContinue')
        return redirect('stockmovements/create')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => optional($stockmovement)->id], 'layouts') . 
					$request->input('document_reference') . ' - ' . $request->input('date') )
// 				->with( compact('date', 'document_reference', 'movement_type_id', 'currency_id') )
				;
        else
		return redirect('stockmovements')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => optional($stockmovement)->id], 'layouts') . 
					$request->input('document_reference') . ' - ' . $request->input('date') );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$stockmove = $this->stockmovement->findOrFail($id);

		return View::make('stock_movements.show', compact('stockmove'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/* ********************************************************************************* */

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// Not delete record, just reverse movement (like in accounting)  ???
		// $this->stockmovement->find($id)->delete();

		// return redirect('stockmovements')
		// 		->with('info', 'El registro se ha eliminado correctamente');
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

//        abi_r($request->all(), true);

        $mvts = $this->stockmovement
        						->filter( $request->all() )
//        						->with('warehouse')
//        						->with('product')
//        						->with('combination')
//        						->with('stockmovementable')
//        						->with('stockmovementable.document')
        						->orderBy('date', 'DESC')
        						->orderBy('id', 'DESC')
                          ->get();

        // Limit number of records
        if ( ($count=$mvts->count()) > 1500 )
        	return redirect()->back()
					->with('error', l('Too many Records for this Query &#58&#58 (:id) ', ['id' => $count], 'layouts'));

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        if ( $request->input('date_from_form') && $request->input('date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('date_from_form') . ' y ' . $request->input('date_to_form');

        } else

        if ( !$request->input('date_from_form') && $request->input('date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('date_to_form');

        } else

        if ( $request->input('date_from_form') && !$request->input('date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('date_from_form');

        } else

        if ( !$request->input('date_from_form') && !$request->input('date_to_form') )
        {
            $ribbon = 'todas';

        }

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Movimientos de Stock -::- '.date('d M Y H:i:s'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];		//, date('d M Y H:i:s')];
        $data[] = ['Fechas: ' . $ribbon];
        $data[] = [''];

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


        $styles = [
            'A5:AC5'    => ['font' => ['bold' => true]],
//            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
//            "D$n:D$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'B' => NumberFormat::FORMAT_TEXT,
//            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//            'D' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2', 'A3:C3', 'A4:C4'];

        $sheetTitle = 'Movimientos de Stock';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');




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