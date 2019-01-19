<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\StockMovement;
use View;

use App\Traits\DateFormFormatterTrait;

class StockAdjustmentsController extends Controller
{
   
   use DateFormFormatterTrait;

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
		$movement_type_id = 12;
		// Valid type? For sure...
		// $this->validate($request, StockMovement::validTypeRule());
		
		$product_id = $request->input('product_id');

		// Has Combination?
		if ($request->has('group')) {
			$combination_id = \App\Combination::getCombinationByOptions( $request->input('product_id'), $request->input('group') );
			$request->merge(array('combination_id' => $combination_id));
		} else {
			$combination_id = null;
		}

		$rules = StockMovement::getRules( $movement_type_id );
		unset( $rules['movement_type_id'] );
		if ( !$combination_id ) unset( $rules['combination_id'] );

		$this->validate($request, $rules);
/*
		// Has Combination?
		if ($request->has('group')) {
			$combination = \App\Combination::find( $combination_id );
			$current_stock = $combination->getStockByWarehouse( $request->input('warehouse_id') );
		} else {
			$product = \App\Product::find( $request->input('product_id') );
			$current_stock = $product->getStockByWarehouse( $request->input('warehouse_id') );
		}

		$new_stock = $request->input('quantity');
*/ 
		$currency = \App\Currency::find( \App\Configuration::get('DEF_CURRENCY') );
		$conversion_rate = $currency->conversion_rate;

		$extradata = ['date' =>  \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('date') ), 
					  'model_name' => '', 
					  'document_id' => 0, 
					  'document_line_id' => 0, 
					  'combination_id' => $combination_id, 
					  'movement_type_id' => $movement_type_id, 
					  'currency_id' => $currency->id,
					  'conversion_rate' => $conversion_rate,
					  'user_id' => \Auth::id()];

		$data = array_merge( $request->all(), $extradata );

        // Product
        if ($combination_id>0) {
            $combination = \App\Combination::with('product')->find(intval($combination_id));
            $product = $combination->product;
            $product->reference = $combination->reference;
            $product->name = $product->name.' | '.$combination->name;
        } else {
            $product = \App\Product::findOrFail(intval($product_id));
        }

        $extradata['reference']  = $product->reference;
        $extradata['name']       = $product->name;


//		$stockmovement = $this->stockmovement->create( array_merge( $request->all(), $extradata ) );
		$stockmovement = $this->stockmovement->createAndProcess( array_merge( $data, $extradata ) );

		$msg = $stockmovement ? l('This record has been successfully created &#58&#58 (:id) ', ['id' => $stockmovement->id], 'layouts')
							  : l('Counted quantity matches current stock on hand. ').l('No action is taken &#58&#58 (:id) ', ['id' => $product->name.' - '.$request->input('quantity')], 'layouts');

		$msg_type = $stockmovement ? 'success'
								   : 'info';

		return redirect()->back()
				->with($msg_type, $msg . 
					' [' . $request->input('date') . ']' );
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