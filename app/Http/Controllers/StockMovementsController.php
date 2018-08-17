<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\StockMovement as StockMovement;
use View, Cookie;

class StockMovementsController extends Controller {


   protected $stockmovement;

   public function __construct(StockMovement $stockmovement)
   {
        $this->stockmovement = $stockmovement;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $mvts = StockMovement::with('warehouse')->with('product')->with('combination')->orderBy('created_at', 'ASC')->get();
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
		$document_reference = Cookie::get('document_reference') ? Cookie::get('document_reference') : '';
		$movement_type_id   = Cookie::get('movement_type_id')   ? Cookie::get('movement_type_id')   : '0';
		$currency_id        = Cookie::get('currency_id')        ? Cookie::get('currency_id')        : \App\Context::getContext()->currency->id;

		return View::make('stock_movements.create', compact('date', 'document_reference', 'movement_type_id', 'currency_id'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// Valid type?
		$this->validate($request, StockMovement::validTypeRule());

        if ( $request->input('movement_type_id') == 10 ) {
        	$product = \App\Product::find( $request->input('product_id') );
            if ( $product->quantity_onhand > 0.0 )
				return redirect('stockmovements/create')
						->with( 'error', l('Can not set Initial Stock &#58&#58 (:id) :name has already non zero stock', ['id' => $product->id, 'name' => $product->name]) );
        }

		// Move on
		$action = $request->input('nextAction', '');

		// Has Combination?
		if ($request->has('group')) {
			$combination_id = \App\Combination::getCombinationByOptions( $request->input('product_id'), $request->input('group') );
			$request->merge(array('combination_id' => $combination_id));
		} else {
			$combination_id = null;
		}

		$conversion_rate = \App\Currency::find($request->input('currency_id'))->conversion_rate;

		$date_raw = $request->input('date');
		// https://styde.net/componente-carbon-fechas-laravel-5/
		// https://es.stackoverflow.com/questions/57020/validaci%C3%B3n-de-formato-de-fecha-no-funciona-laravel-5-3
		$date_view = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('date') );

//		abi_r($date_view->toDateTimeString());
//		abi_r($date_view->toDateString(), true);
 
		$extradata = ['date' =>  $date_view->toDateString(), 
					  'combination_id' => $combination_id, 
					  'conversion_rate' => $conversion_rate, 
//					  'user_id' => \Auth::id()
					  ];

		$this->validate($request, StockMovement::getRules( $request->input('movement_type_id') ));

		$stockmovement = $this->stockmovement->create( array_merge( $request->all(), $extradata ) );

		// Time savers
		Cookie::queue('date', $date_raw, 1);	// Live 1 minute
		Cookie::queue('document_reference', $request->input('document_reference'), 1);
		Cookie::queue('movement_type_id',   $request->input('movement_type_id'), 1);
		Cookie::queue('currency_id',        $request->input('currency_id'), 1);

		// Stock movement fulfillment (perform stock movements)
		$stockmovement->process();

        if ($action == 'saveAndContinue')
        return redirect('stockmovements/create')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $stockmovement->id], 'layouts') . 
					$request->input('document_reference') . ' - ' . $request->input('date') )
				->with( compact('date', 'document_reference', 'movement_type_id', 'currency_id') );
        else
		return redirect('stockmovements')
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $stockmovement->id], 'layouts') . 
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

}