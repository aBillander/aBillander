<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\CustomerShippingSlip;

class ChartCustomerSalesController extends Controller
{
    // https://www.youtube.com/watch?v=HGPLf8JBDs4&t=469s
    // https://github.com/imranhsayed/laravel-charts

   protected $models = ['CustomerOrder', 'CustomerShippingSlip', 'CustomerInvoice'];

	
	function getMonthlySales(Request $request) {

		$model = $this->getRequestModel($request);

		return view('charts.customer_documents', compact('model'));
    }


	function getAllMonths( $model = 'CustomerOrder' ){
		$month_array = array();
		$class = 'App\\'.$model;
		$orders_dates = $class::orderBy( 'created_at', 'ASC' )->pluck( 'created_at' );
		$orders_dates = json_decode( $orders_dates );
		if ( ! empty( $orders_dates ) ) {
			foreach ( $orders_dates as $unformatted_date ) {
				$date = new \DateTime( $unformatted_date->date );
				$month_no = $date->format( 'm' );
				$month_name = l('month.'.$month_no);	//$date->format( 'M' );
				$month_array[ $month_no ] = $month_name;
			}
		}
		return $month_array;
	}

	function getMonthlyPostCount( $month, $model = 'CustomerOrder' ) {
//		$monthly_order_count = CustomerShippingSlip::whereMonth( 'created_at', $month )->get()->count();
		$class = 'App\\'.$model;
		$monthly_order_count = $class::select('total_tax_excl')->whereMonth( 'document_date', $month )->get()->sum('total_tax_excl');
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
	
	function getMonthlySalesData(Request $request) {

		$model = $this->getRequestModel($request);

		$monthly_order_count_array = array();
		$month_array = $this->getAllMonths( $model );

		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_order_count = $this->getMonthlyPostCount( $month_no, $model );
				array_push( $monthly_order_count_array, $monthly_order_count );
				array_push( $month_name_array, $month_name );
			}
		}
		$max_no = max( $monthly_order_count_array );
		$max = round(( $max_no + 10/2 ) / 10 ) * 10;
		$monthly_order_data_array = array(
			'months' => $month_name_array,
			'sales_data' => $monthly_order_count_array,
			'max' => $max,
		);
		return $monthly_order_data_array;
    }

	
	function getRequestModel(Request $request) {

		if ( $request->has('CustomerInvoice'))
			$model = 'CustomerInvoice';
		else

		if ( $request->has('CustomerShippingSlip'))
			$model = 'CustomerShippingSlip';
		else

			$model = 'CustomerOrder';

		return $model;

    }
}
