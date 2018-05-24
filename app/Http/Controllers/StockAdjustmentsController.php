<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\StockMovement as StockMovement;
use View;

class StockAdjustmentsController extends Controller {


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
        //
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('stock_adjustments.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// Has Combination?
		if ($request->has('group')) {
			$combination_id = \App\Combination::getCombinationByOptions( $request->input('product_id'), $request->input('group') );
			$request->merge(array('combination_id' => $combination_id));
		} else {
			$combination_id = 0;
		}

		$this->validate($request, StockMovement::$rules_adjustment);

		// Has Combination?
		if ($request->has('group')) {
			$combination = \App\Combination::find( $combination_id );
			$current_stock = $combination->getStockByWarehouse( $request->input('warehouse_id') );
		} else {
			$product = \App\Product::find( $request->input('product_id') );
			$current_stock = $product->getStockByWarehouse( $request->input('warehouse_id') );
		}

		$new_stock = $request->input('quantity');
 
		$extradata = ['date' =>  \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('date') ), 
					  'model_name' => '', 'document_id' => 0, 'document_line_id' => 0, 'combination_id' => $combination_id, 'movement_type_id' => 12, 'user_id' => \Auth::id()];

		$data = array_merge( $request->all(), $extradata );

		// Empty stock
		// This movement keeps track of current stock, even if it is zero.
		$stockmovement = $this->stockmovement->create( array_merge( $data, ['quantity' => -$current_stock] ) );
		$stockmovement->fulfill();

		// Refill new stock
		$stockmovement_adj = $this->stockmovement->create( $data );
		$stockmovement_adj->fulfill();

		return redirect()->back()
				->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $stockmovement_adj->id], 'layouts') . 
					' - ' . $request->input('date') );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
		// 
	}

}