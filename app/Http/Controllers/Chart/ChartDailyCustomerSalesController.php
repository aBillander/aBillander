<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\CustomerShippingSlip;

class ChartDailyCustomerSalesController extends Controller
{
    // https://www.youtube.com/watch?v=HGPLf8JBDs4&t=469s
    // https://github.com/imranhsayed/laravel-charts

   protected $models = ['CustomerOrder', 'CustomerShippingSlip', 'CustomerInvoice'];

   public $first;

	function getDailySales(Request $request) {

		$model = $this->getRequestModel($request);

		return view('charts.customer_documents_daily', compact('model'));
    }


	function getAllMonths( $model = 'CustomerOrder' ){
		$class = '\\App\\Models\\'.$model;
		// 12 months (maximum) range
		$last = $class::
							  orderBy( 'document_date', 'DESC' )
							->first()
							->document_date;

		// Better today:
		$last = \Carbon\Carbon::now();
		
		// $this->first = $last->copy()->subDays( 6 + 3*7 + 1);	// 6 days plus current one makes 1 week, plus 3 weeks (3*7 days) makes 4 weeks, i.e. almost a month
		// Added 1 extra day to make chart prettier

		// Duh! Only last seven days
		$this->first = $last->copy()->subDays( 6 + 0*3*7 + 1);

		// abi_r( $last );
		// abi_r( $this->first );die();

		$month_array = array();
		$orders_dates = $class::
							  where( 'close_date', '>=', $this->first )
							->orderBy( 'close_date', 'ASC' )
							->pluck( 'close_date' );
		// abi_r($orders_dates[0]);abi_r('*********************');
//		$orders_dates = json_decode( $orders_dates );	<== Somehow timezone is changed and date is altered!
		// abi_r($orders_dates[0]);abi_r('*********************');die();
		if ( ! empty( $orders_dates ) ) {
			foreach ( $orders_dates as $unformatted_date ) {
//				$date = new \DateTime( $unformatted_date->date );
				// $date = new \DateTime( $unformatted_date );
//				$month_no = $date->format( 'Y-m-d' );
				$month_no = $unformatted_date->format( 'Y-m-d' );
				$month_name = l('month.'.$month_no);	//$date->format( 'M' );
				// $month_array[ $month_no ] = $month_name." ".$date->format( 'Y' );
				$month_array[ $month_no ] = $month_no." ".l('day.'.$unformatted_date->format( 'N' ));
			}
		}
		// abi_r($month_array);die();
		return $month_array;
	}

	function getDailyPostCount( $day, $model = 'CustomerOrder' ) {
//		$monthly_order_count = CustomerShippingSlip::whereMonth( 'created_at', $month )->get()->count();
		$class = '\\App\\Models\\'.$model;
		$monthly_order_count = $class::
									  select('total_tax_excl')
									->whereDate( 'close_date', $day )	// See: https://stackoverflow.com/questions/25139948/laravel-eloquent-compare-date-from-datetime-field
//									->where( 'document_date', '>=', $this->first )
									->get()
									->sum('total_tax_excl');
		return round($monthly_order_count, 2);
	}
	
	function getDailyPostData() {
		$monthly_order_count_array = array();
		$month_array = $this->getAllMonths();
		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_order_count = $this->getDailyPostCount( $month_no );
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
	
	// Heavy lifting is done here, and returns chart data
	function getDailySalesData(Request $request) {

		$model = $this->getRequestModel($request);

		$monthly_order_count_array = array();
		$month_array = $this->getAllMonths( $model );

		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_order_count = $this->getDailyPostCount( $month_no, $model );
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
