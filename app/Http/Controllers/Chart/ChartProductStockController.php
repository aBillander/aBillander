<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\StockMovement;

class ChartProductStockController extends Controller
{
    // https://www.youtube.com/watch?v=HGPLf8JBDs4&t=469s
    // https://github.com/imranhsayed/laravel-charts


	protected $product;

//	public function __construct(Product $product)
//	{
//	    $this->product = $product;
//	}

	
	function getMonthlyProductStock(Request $request) {

		$product = $this->product = Product::with('measureunit')->findOrFail( $request->input('product_id', 0));

		$has_data = StockMovement::where('product_id', $product->id)->count();

		return view('charts.product_stock', compact('product', 'has_data'));
    }


	function getAllMonths( ){

		$product = $this->product;

		// 12 months (maximum) range
		$last_record = StockMovement::
							  where('product_id', $product->id)
/*                            ->where( function($query) {

                                    $query->where(  'status', 'pending');
                                    $query->orWhere('status', 'paid');
                            } )	*/
							->orderBy( 'date', 'DESC' )
							->first();

		if ( !$last_record ) die();

		$last = $last_record->date;
		
		$first = $last->copy()->subMonths(11)->startOfMonth();	// 11 months plus current one makes 12 months, i.e. a year

		// abi_r( $last );
		// abi_r( $first, true );

		$month_array = array();
/*
		$orders_dates = StockMovement::
							  where('product_id', $product->id)
                            ->where( function($query) {

                                    // $query->where(  'status', 'pending');
                                    // $query->orWhere('status', 'paid');
                            } )
                            ->where( 'date', '>=', $first )
							->orderBy( 'date', 'ASC' )
							->pluck( 'date' );
		// abi_r($orders_dates[0]);abi_r('*********************');
*/		
		// Let's try another way
		$orders_dates = collect([]);
		for ($i=0; $i < 12; $i++) { 
			# code...
			$orders_dates[] = $last->copy()->subMonths( 11 - $i )->startOfMonth();
		}


		$orders_dates = json_decode( $orders_dates );
		// abi_r($orders_dates[0]);abi_r('*********************');
		if ( ! empty( $orders_dates ) ) {
			foreach ( $orders_dates as $unformatted_date ) {
				$date = new \DateTime( $unformatted_date->date );
				$month_no = $date->format( 'm' );
				$month_name = l('month.'.$month_no);	//$date->format( 'M' );
				$month_array[ $month_no ] = $month_name." ".$date->format( 'Y' );
			}
		}
		// abi_r($orders_dates[0]);abi_r('*********************');die();
		// abi_r($orders_dates);abi_r('*********************');die();
		// abi_r($month_array);die();
		
		// Only months with data:
		return $month_array;
	}

	function getMonthlyPostCount( $month ) 
	{
		$records = StockMovement::
								  where('product_id', $this->product->id)
	                            ->where( function($query) {

	                                    // $query->where(  'status', 'pending');
	                                    // $query->orWhere('status', 'paid');
	                            } )
								->select('movement_type_id', 'quantity')
								->whereMonth( 'date', $month )
								->get()
//								->sum('quantity')
								;

		// abi_r($records);die();
		
		$monthly_order_count =    $records->where( 'movement_type_id', StockMovement::PURCHASE_ORDER )->sum('quantity')
								- $records->where( 'movement_type_id', StockMovement::PURCHASE_RETURN )->sum('quantity')
								+ $records->where( 'movement_type_id', StockMovement::MANUFACTURING_OUTPUT )->sum('quantity')
								;

		return round($monthly_order_count, 2);
	}

	function getMonthlyPostPendingCount( $month )
	{
		$records = StockMovement::
								  where('product_id', $this->product->id)
	                            ->where( function($query) {

	                                    // $query->where(  'status', 'pending');
	                                    // $query->orWhere('status', 'paid');
	                            } )
								->select('movement_type_id', 'quantity')
								->whereMonth( 'date', $month )
								->get()
//								->sum('quantity')
								;

		// abi_r($records);die();
		
		$monthly_order_count =    $records->where( 'movement_type_id', StockMovement::SALE_ORDER )->sum('quantity')
								- $records->where( 'movement_type_id', StockMovement::SALE_RETURN )->sum('quantity')
								+ $records->where( 'movement_type_id', StockMovement::MANUFACTURING_INPUT )->sum('quantity')
								- $records->where( 'movement_type_id', StockMovement::MANUFACTURING_RETURN )->sum('quantity')
								- $records->where( 'movement_type_id', StockMovement::ADJUSTMENT )->sum('quantity')
								;
		
		return round($monthly_order_count, 2);
	}
	
	function getMonthlyPostData() {
		$monthly_order_count_array = array();
		$month_array = $this->getAllMonths();
		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_order_count = $this->getMonthlyPostCount( $month_no );
				array_push( $monthly_order_count_array, $monthly_order_count );
				array_push( $month_name_array, $month_name );
			}
		}
		$max_no = max( $monthly_order_count_array );
		$max = round(( $max_no + 10/2 ) / 10 ) * 10;
		$monthly_order_data_array = array(
			'months' => $month_name_array,
			'order_count_data' => $monthly_order_count_array,
			'max' => $max,
		);
		return $monthly_order_data_array;
    }
	
	
	// Magic happens here:
	function getMonthlyProductStockData(Request $request) {

		$product = $this->product = Product::findOrFail( $request->input('product_id', 0));

		$monthly_order_count_array = array();
		$monthly_pending_count_array = array();
		$monthly_diff_count_array = array();
		$month_array = $this->getAllMonths( );

		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_order_count = $this->getMonthlyPostCount( $month_no );
				$monthly_pending_count = $this->getMonthlyPostPendingCount( $month_no );
				array_push( $monthly_order_count_array, $monthly_order_count );
				array_push( $monthly_pending_count_array, $monthly_pending_count );
				array_push( $monthly_diff_count_array, $monthly_pending_count - $monthly_order_count );
				array_push( $month_name_array, $month_name );
			}
		}
		$max_no = max( max( $monthly_order_count_array ), max( $monthly_pending_count_array ) );
		$max = ceil(( $max_no ) / 10 ) * 10;
		$monthly_order_data_array = array(
			'months' => $month_name_array,
			'sales_data' => $monthly_order_count_array,
			'pending_data' => $monthly_pending_count_array,
			'diff_data' => $monthly_diff_count_array,
			'min' => min( min( $monthly_order_count_array ), min( $monthly_pending_count_array ) ),
			'max' => $max,
		);
		return $monthly_order_data_array;
    }

}
