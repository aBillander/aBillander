<?php 

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Payment as Payment;

class CustomerDownPaymentsController extends Controller {


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
					->with('customerInvoice')
					->with('customer')
					->where('model_name', '=', 'CustomerInvoice')
					->orderBy('id', 'asc')->get();

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
		
		$payment = $this->payment->findOrFail($id);
		
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
		$back_route = ( $request->has('back_route') AND $request->input('back_route')) ? urldecode($request->input('back_route')) : 'customervouchers' ;

		$payment = $this->payment->findOrFail($id);

		$this->validate($request, Payment::$rules);

		if ( $request->input('status') == 'paid' ) {
			if ( !$request->input('payment_date') ) $request->merge( array('payment_date' => abi_date_short( \Carbon\Carbon::now() ) ) );
		} else {
			if (  $request->input('payment_date') ) $request->merge( array('payment_date' => null ) );
		}

		$diff = $payment->amount - $request->input('amount');

		$payment->update($request->all());

		if ( $diff > 0 ) {
			$new_payment = $payment->replicate( ['id', 'due_date', 'payment_date', 'amount'] );

			$new_payment->due_date = abi_date_short( \Carbon\Carbon::now() );
			$new_payment->payment_date = NULL;
			$new_payment->amount = $diff;

			$new_payment->save();

			// ToDo: Update invoice balance & next due date
		}

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
