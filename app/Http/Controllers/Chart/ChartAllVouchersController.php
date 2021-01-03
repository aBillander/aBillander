<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Payment;

class ChartAllVouchersController extends Controller
{
    // https://www.youtube.com/watch?v=HGPLf8JBDs4&t=469s
    // https://github.com/imranhsayed/laravel-charts

   public $first;

	function getMonthlyVouchers(Request $request) {

		return view('charts.all_vouchers');
    }


	function getAllMonths( ){
		// 12 months (maximum) range
		$last = Payment::
//							  where('payment_type', 'payable')
                              where( function($query) {

                                    $query->where(  'status', 'pending');
                                    $query->orWhere('status', 'paid');
                            } )
							->orderBy( 'due_date', 'DESC' )
							->first()
							->due_date;
		
		$this->first = $last->copy()->subMonths(11)->startOfMonth();	// 11 months plus current one makes 12 months, i.e. a year

		// abi_r( $date );
		// abi_r( $this->first, true );

		$month_array = array();
		$orders_dates = Payment::
//							  where('payment_type', 'payable')
                              where( function($query) {

                                    $query->where(  'status', 'pending');
                                    $query->orWhere('status', 'paid');
                            } )
                            ->where( 'due_date', '>=', $this->first )
							->orderBy( 'due_date', 'ASC' )
							->pluck( 'due_date' );
		// abi_r($orders_dates[0]);abi_r('*********************');
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
		return $month_array;
	}

	function getMonthlyPostCount( $month ) {
//		$monthly_order_count = SupplierShippingSlip::whereMonth( 'created_at', $month )->get()->count();		
		$monthly_order_count_receivable = Payment::
									  where('payment_type', 'receivable')
		                            ->where( function($query) {

		                                    $query->where(  'status', 'pending');
		                                    $query->orWhere('status', 'paid');
		                            } )
									->select('amount')
									->whereMonth( 'due_date', $month )
									->where( 'due_date', '>=', $this->first )
									->get()
									->sum('amount');
		
		$monthly_order_count_payable = Payment::
									  where('payment_type', 'payable')
		                            ->where( function($query) {

		                                    $query->where(  'status', 'pending');
		                                    $query->orWhere('status', 'paid');
		                            } )
									->select('amount')
									->whereMonth( 'due_date', $month )
									->where( 'due_date', '>=', $this->first )
									->get()
									->sum('amount');

		return round($monthly_order_count_receivable - $monthly_order_count_payable, 2);
	}

	function getMonthlyPostPendingCount( $month ) {
//		$monthly_order_count = SupplierShippingSlip::whereMonth( 'created_at', $month )->get()->count();
		$monthly_order_count_receivable = Payment::
									  where('payment_type', 'receivable')
									->where('status', 'pending')
									->select('amount')
									->whereMonth( 'due_date', $month )
									->where( 'due_date', '>=', $this->first )
									->get()
									->sum('amount');
		
		$monthly_order_count_payable = Payment::
									  where('payment_type', 'payable')
									->where('status', 'pending')
									->select('amount')
									->whereMonth( 'due_date', $month )
									->where( 'due_date', '>=', $this->first )
									->get()
									->sum('amount');
		
		return round($monthly_order_count_receivable - $monthly_order_count_payable, 2);
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
	
	// Heavy lifting is done here, and returns vouchers data
	function getMonthlyVouchersData(Request $request) {

		$monthly_order_count_array = array();
		$monthly_pending_count_array = array();
		$month_array = $this->getAllMonths( );

		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_order_count = $this->getMonthlyPostCount( $month_no );
				$monthly_pending_count = $this->getMonthlyPostPendingCount( $month_no );
				array_push( $monthly_order_count_array, $monthly_order_count );
				array_push( $monthly_pending_count_array, $monthly_pending_count );
				array_push( $month_name_array, $month_name );
			}
		}
		$max_no = max( $monthly_order_count_array );
		$max = round(( $max_no + 10/2 ) / 10 ) * 10;
		
		$min_no = min( $monthly_order_count_array );
		$min = round(( $min_no - 10/2 ) / 10 ) * 10;

		$monthly_order_data_array = array(
			'months' => $month_name_array,
			'sales_data' => $monthly_order_count_array,
			'pending_data' => $monthly_pending_count_array,
			'max' => $max,
			'min' => $min,
		);
		return $monthly_order_data_array;
    }

}
