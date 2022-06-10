<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Configuration;

use Carbon\Carbon;

use App\Models\Product;
use App\Models\StockMovement;

class ChartProductSalesController extends Controller
{
    // https://www.youtube.com/watch?v=HGPLf8JBDs4&t=469s
    // https://github.com/imranhsayed/laravel-charts


	protected $models = ['CustomerOrder', 'CustomerShippingSlip', 'CustomerInvoice'];

	protected $product;

//	public function __construct(Product $product)
//	{
//	    $this->product = $product;
//	}

	
	function getMonthlyProductSales(Request $request) {

		$product = $this->product = Product::with('measureunit')->findOrFail( $request->input('product_id', 0));

		// $has_data = StockMovement::where('product_id', $product->id)->count();
		$has_data = true;

		$labels = ['sales_data' => Carbon::now()->year, 'pending_data' => Carbon::now()->year - 1];

		return view('charts.product_sales', compact('product', 'has_data', 'labels'));
    }


	function getAllMonths( ){

        $current_year = $this->current_year = Carbon::now()->year;
        $current_month = $this->current_month = Carbon::now()->month;

		$product = $this->product;

		// 12 months range
		$last = Carbon::create($current_year, 12, 1)->startOfDay();	// December

		$orders_dates = collect([]);
		for ($i=0; $i < 12; $i++) { 
			# code...
			$orders_dates[] = $last->copy()->subMonths( 11 - $i )->startOfMonth();
		}

		// abi_r($orders_dates);


//		$orders_dates = json_decode( $orders_dates );	<== Somehow timezone is changed and date is altered!
		// abi_r($orders_dates[0]);abi_r('*********************');
		if ( ! empty( $orders_dates ) ) {
			foreach ( $orders_dates as $unformatted_date ) {
//				$date = new \DateTime( $unformatted_date->date );
				// $date = new \DateTime( $unformatted_date );
//				$month_no = $date->format( 'm' );
				$month_no = $unformatted_date->format( 'm' );
				$month_name = l('month.'.$month_no);	//$date->format( 'M' );
				$month_array[ $month_no ] = $month_name;	// ." ".$date->format( 'Y' );
			}
		}
		// abi_r($orders_dates[0]);abi_r('*********************');die();
		// abi_r($orders_dates);abi_r('*********************');die();
		// abi_r($month_array);die();
		
		// Only months with data:
		return $month_array;
	}

	function getMonthlyPostCount( $month, $year ) 
	{
        $product = $this->product;

        $models = $this->models;
        $model = $this->model;
        if ( !in_array($model, $models) )
            $model = Configuration::get('RECENT_SALES_CLASS');
        $class = '\\App\\Models\\'.$model.'Line';
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);

        $document_reference_date = 'close_date';        // 'document_date'

        $document_total_tax = $this->document_total_tax;

        $is_invoiceable_flag = ($model == 'CustomerShippingSlip') ? true : false;

	    $product_date_from = Carbon::create($year, $month, 1)->startOfDay();
	    $product_date_to   = $product_date_from->copy()->endOfMonth()->endOfDay();


		$monthly_order_count = $class::
                          where('line_type', 'product')
                        ->where('product_id', $product->id)
                        ->whereHas('document', function ($query) use ( $product_date_from, $product_date_to, $document_reference_date, $is_invoiceable_flag) {

                                // Closed Documents only
                                $query->where($document_reference_date, '!=', null);

                                // Only invoiceable Documents when Documents are Customer Shipping Slips
                                if ( $is_invoiceable_flag )
                                    $query->where('is_invoiceable', '>', 0);

                                if ( $product_date_from )
                                    $query->where($document_reference_date, '>=', $product_date_from);
                                
                                if ( $product_date_to )
                                    $query->where($document_reference_date, '<=', $product_date_to);
                        })
                        ->sum($document_total_tax);

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
	
	
	// Entry point
	// Magic happens here:
	function getMonthlyProductSalesData(Request $request) {

        // Prepar. See: ReportsController
        $product_sales_month_from = $this->product_sales_month_from = $request->input('product_sales_month_from', 1);
        
        $product_sales_month_to   = $this->product_sales_month_to   = $request->input('product_sales_month_to', 12 );

        // $nbr_years = $request->input('product_sales_years_to_compare', 1);

        $document_total_tax = $this->document_total_tax = $request->input('product_sales_value', 'total_tax_incl');

        if ( !in_array($document_total_tax, ['total_tax_incl', 'total_tax_excl']) )
            $document_total_tax = $this->document_total_tax = 'total_tax_incl';
 
        $model = $this->model = $request->input('product_sales_model', Configuration::get('RECENT_SALES_CLASS'));

		// In the end:
		$product = $this->product = Product::findOrFail( $request->input('product_id', 0));


		$monthly_order_count_array = array();
		$monthly_pending_count_array = array();
		$monthly_diff_count_array = array();

		$month_array = $this->getAllMonths( );

		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_order_count   = $this->getMonthlyPostCount( $month_no, $this->current_year     );
				$monthly_pending_count = $this->getMonthlyPostCount( $month_no, $this->current_year - 1 );
				array_push( $monthly_order_count_array,   $monthly_order_count   );
				array_push( $monthly_pending_count_array, $monthly_pending_count );
				array_push( $monthly_diff_count_array, $monthly_pending_count - $monthly_order_count );
				array_push( $month_name_array, $month_name );
			}
		}

		// abi_r($request->all());

		// Cumulative
		if ( $request->has('cumulative') && ($request->input('cumulative') == 1) )
		for ($i=1; $i < 12; $i++) { 
			# code...
			$monthly_order_count_array[$i]   += $monthly_order_count_array[$i-1];
			$monthly_pending_count_array[$i] += $monthly_pending_count_array[$i-1];
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
