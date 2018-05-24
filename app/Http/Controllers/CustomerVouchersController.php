<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Payment as Payment;

class CustomerVouchersController extends Controller {


   protected $payment;

   public function __construct(Payment $payment)
   {
        $this->payment = $payment;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$payments = $this->payment
					->with('paymentable')
					->with('paymentable.customer')
					->where('payment_type', 'receivable')
					->orderBy('due_date', 'desc')->get();

        return view('customer_vouchers.index', compact('payments'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
	public function edit($id, Request $request)
	{
		$back_route = $request->has('back_route') ? urldecode($request->input('back_route')) : '' ;
		
		$payment = $this->payment->with('currency')->findOrFail($id);
		
		return view('customer_vouchers.edit', compact('payment', 'back_route'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$back_route = ( $request->has('back_route') AND $request->input('back_route') ) ? urldecode($request->input('back_route')) : 'customervouchers' ;

		$payment = $this->payment->findOrFail($id);

		if ( 1 ) {

			$rules = Payment::$rules;
			if ( $request->input('action', '') == 'pay' ) $rules['payment_date']  = 'required';
			if ( $request->input('amount_next', 0.0)    ) $rules['due_date_next'] = 'required';
			$rules['amount'] .= $payment->amount;

			$this->validate($request, $rules);

			$diff = $payment->amount - $request->input('amount');

			if ( !$request->input('payment_date') ) $request->merge( array('payment_date' => abi_date_short( \Carbon\Carbon::now() ) ) );

			$payment->fill($request->all());

			// If amount is not fully paid, a new payment will be created for the difference
			if ( $diff > 0 ) {
				$new_payment = $payment->replicate( ['id', 'due_date', 'payment_date', 'amount'] );

				$due_date = $request->input('due_date_next') ?
						  \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $request->input('due_date_next') ) :
						  \Carbon\Carbon::now();

				$new_payment->name = $payment->name . ' * ';
				$new_payment->status = 'pending';
				$new_payment->due_date = abi_date_short( $due_date );
				$new_payment->payment_date = NULL;
				$new_payment->amount = $diff;

				$new_payment->save();

				$payment->status = 'paid';
			} else {
				$payment->status = 'paid';
			}

			$payment->save();

			return redirect($back_route)
					->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name') . ' / ' . $request->input('due_date'));
		}

		$this->validate($request, Payment::$rules);

		$diff = $payment->amount - $request->input('amount');

		if ( ($request->input('status') == 'paid') || ($diff == 0) ) {
			$request->merge( ['amount' => $payment->amount] );
			if ( !$request->input('payment_date') ) $request->merge( array('payment_date' => abi_date_short( \Carbon\Carbon::now() ) ) );
		}

		$payment->fill($request->all());

		// If amount is not fully paid, a new payment will be created for the difference
		if ( $diff > 0 ) {
			$new_payment = $payment->replicate( ['id', 'due_date', 'payment_date', 'amount'] );

			$new_payment->name = $payment->name . ' * ';
			$new_payment->due_date = abi_date_short( \Carbon\Carbon::now() );
			$new_payment->payment_date = NULL;
			$new_payment->amount = $diff;

			$new_payment->save();

			$payment->status = 'paid';
		} else {
			$payment->status = 'paid';
		}

		$payment->save();

			// ToDo: Update invoice balance & next due date

			// ToDo: Update Customer risk

		return redirect($back_route)
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name') . ' / ' . $request->input('due_date'));
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
