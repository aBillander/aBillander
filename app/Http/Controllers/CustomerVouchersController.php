<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Customer;

use App\Payment;
use App\Configuration;

use aBillander\SepaSpain\SepaDirectDebit;

use App\Traits\DateFormFormatterTrait;

use App\Events\CustomerPaymentReceived;
use App\Events\CustomerPaymentBounced;

class CustomerVouchersController extends Controller
{
   
   use DateFormFormatterTrait;


   protected $customer;
   protected $payment;

   public function __construct(Customer $customer, Payment $payment)
   {
        $this->customer = $customer;

        $this->payment = $payment;
   }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        // dd($request->all());

		$payments = $this->payment
        			->filter( $request->all() )
					->with('paymentable')
					->with('paymentable.customer')
					->with('paymentable.customer.bankaccount')
					->where('payment_type', 'receivable')
					->with('bankorder')
					->orderBy('due_date', 'asc');		// ->get();

        $payments = $payments->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $payments->setPath('customervouchers');

        $statusList = Payment::getStatusList();

        $sdds = SepaDirectDebit::where('status', 'pending')->orWhere('status', 'confirmed')
        						->orderBy('document_date', 'desc')->orderBy('id', 'desc')
        						->get();

        // abi_r($sdds->pluck('document_reference', 'id')->toArray()); 

        $sddList = [];
        foreach ($sdds as $sdd) {
        	# code...
        	$sddList[$sdd->id] = $sdd->document_reference.' :: '.$sdd->status_name.' :: '.abi_date_short($sdd->document_date);
        }

        // abi_r($sddList);die();

        return view('customer_vouchers.index', compact('payments', 'statusList', 'sddList'));
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function indexByCustomer($id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');

        $customer = $this->customer->findOrFail($id);

		$payments = $this->payment
                    ->whereHas('customer', function ($query) use ($id) {
                            $query->where('id', $id);
                        })
        			->filter( $request->all() )
					->with('paymentable')
					->with('paymentable.customer')
					->with('paymentable.customer.bankaccount')
					->where('payment_type', 'receivable')
					->with('bankorder')
					->orderBy('due_date', 'asc');

        $payments = $payments->paginate( $items_per_page );

        $payments->setPath($id);

        return view('customer_vouchers.index_by_customer', compact('customer', 'payments', 'items_per_page'));
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
		$action = $request->input('action', 'edit') ?: 'edit';
		$back_route = ( $request->has('back_route') AND $request->input('back_route') ) ? urldecode($request->input('back_route')) : '' ;
		
		$payment = $this->payment->with('currency')->findOrFail($id);

		// abi_r($payment, true);

        // Dates (cuen)
        $this->addFormDates( ['due_date'], $payment );
		
		return view('customer_vouchers.edit', compact('payment', 'action', 'back_route'));
	}

	public function setduedate($id, Request $request)
	{
		$action = 'setduedate';
		$back_route = ( $request->has('back_route') AND $request->input('back_route') ) ? urldecode($request->input('back_route')) : 'customervouchers' ;
		
		$payment = $this->payment->with('currency')->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['due_date'], $payment );
		
		return view('customer_vouchers.edit', compact('payment', 'action' , 'back_route'));
	}


	public function pay($id, Request $request)
	{
		$action = 'pay';
		$back_route = ( $request->has('back_route') AND $request->input('back_route') ) ? urldecode($request->input('back_route')) : 'customervouchers' ;
		
		$payment = $this->payment->with('currency')->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['due_date'], $payment );
		
		return view('customer_vouchers.edit', compact('payment', 'action' , 'back_route'));
	}

	public function payVouchers(Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['payment_date'], $request );
		
		// Validate input (to do)
		$rules = [
            'payment_date' => 'required|date',
		];
        $this->validate($request, $rules);

		// $payment_date = ;

		$list = $request->input('vouchers', []);

        if ( count( $list ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts') . l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));


		// abi_r($list);die();
        $payments = $this->payment->whereIn('id', $list)->where('status', 'pending')->get();

        // Do the Mambo!
        foreach ( $payments as $payment ) 
        {
        	$payment->payment_date = $request->input('payment_date');

			$payment->status   = 'paid';
			$payment->save();

			// Update Customer Risk
			event(new CustomerPaymentReceived($payment));
        }

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') .  $request->input('payment_date_form'));
	}


	public function bounce($id, Request $request)
	{
		$action = 'bounce';
		$back_route = ( $request->has('back_route') AND $request->input('back_route') ) ? urldecode($request->input('back_route')) : 'customervouchers' ;
		
		$payment = $this->payment->with('currency')->findOrFail($id);

        // Dates (cuen)
        $this->addFormDates( ['due_date'], $payment );
		
		return view('customer_vouchers.edit', compact('payment', 'action' , 'back_route'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['due_date', 'payment_date', 'due_date_next'], $request );


		$back_route = ( $request->has('back_route') AND $request->input('back_route') ) ? urldecode($request->input('back_route')) : 'customervouchers' ;

		$action = $request->input('action', '');

		$payment = $this->payment->findOrFail($id);

		if ( $action == 'edit' ) {
			//

			$rules = Payment::$rules;
			if ( $request->input('amount_next', 0.0) ) $rules['due_date_next'] = 'required';
			if ( $payment->amount > 0.0 )
				$rules['amount'] .= '|min:0.0|max:' . $payment->amount;
			else
				$rules['amount'] .= '|max:0.0|min:' . $payment->amount;

			$this->validate($request, $rules);

			$diff = $payment->amount - $request->input('amount', $payment->amount);

			// If amount is not fully paid, a new payment will be created for the difference
			if ( $diff != 0 ) {
				$new_payment = $payment->replicate( ['id', 'due_date', 'payment_date', 'amount'] );

				$due_date = $request->input('due_date_next') ?: \Carbon\Carbon::now();

				$new_payment->name = $payment->name . ' * ';
				$new_payment->status = 'pending';
				$new_payment->due_date = $due_date;
				$new_payment->payment_date = NULL;
				$new_payment->amount = $diff;

				$new_payment->save();

			}

			$payment->name     = $request->input('name',     $payment->name);
			$payment->due_date = $request->input('due_date', $payment->due_date);
			$payment->amount   = $request->input('amount',   $payment->amount);
			$payment->notes    = $request->input('notes',    $payment->notes);

			$payment->auto_direct_debit = $request->input('auto_direct_debit',    $payment->auto_direct_debit);

			$payment->save();


			return redirect($back_route)
					->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name') . ' / ' . $request->input('due_date'));
		
		} else

		if ( $action == 'setduedate' ) {

			$rules = [
				'due_date' => 'required|date',
			];

			$this->validate($request, $rules);

			$payment->due_date = $request->input('due_date', $payment->due_date);

			$payment->save();


			return redirect($back_route)
					->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $payment->name . ' / ' . $request->input('due_date'));
			
		} else

		if ( $action == 'pay' ) {

			// if ( !$request->input('payment_date') ) $request->merge( array('payment_date' => abi_date_short( \Carbon\Carbon::now() ) ) );

			$rules = Payment::$rules;
			$rules['payment_date']  = 'required';
			if ( $request->input('amount_next', 0.0) ) $rules['due_date_next'] = 'required';
			if ( $payment->amount > 0.0 )
				$rules['amount'] .= '|min:0.0|max:' . $payment->amount;
			else
				$rules['amount'] .= '|max:0.0|min:' . $payment->amount;

			$this->validate($request, $rules);

			$diff = $payment->amount - $request->input('amount', $payment->amount);

			// If amount is not fully paid, a new payment will be created for the difference
			if ( $diff != 0 ) {
				$new_payment = $payment->replicate( ['id', 'due_date', 'payment_date', 'amount'] );

				$due_date = $request->input('due_date_next') ?: \Carbon\Carbon::now();

				$new_payment->name = $payment->name . ' * ';
				$new_payment->status = 'pending';
				$new_payment->due_date = $due_date;
				$new_payment->payment_date = NULL;
				$new_payment->amount = $diff;

				$new_payment->save();

			}

			$payment->name     = $request->input('name',     $payment->name);
//			$payment->due_date = $request->input('due_date', $payment->due_date);
			$payment->payment_date = $request->input('payment_date');
			$payment->amount   = $request->input('amount',   $payment->amount);
			$payment->notes    = $request->input('notes',    $payment->notes);

			$payment->status   = 'paid';
			$payment->save();

			// Update Customer Risk
			event(new CustomerPaymentReceived($payment));


			return redirect($back_route)
					->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name') . ' / ' . $request->input('due_date'));
		} else

		if ( $action == 'bounce' ) {
			
			// No action to process
			// return redirect($back_route)					->with('info', 'Bounced!');

			// if ( !$request->input('payment_date') ) $request->merge( array('payment_date' => abi_date_short( \Carbon\Carbon::now() ) ) );

			$rules = Payment::$rules;
			$rules['payment_date']  = 'required';
//			if ( $request->input('amount_next', 0.0) ) $rules['due_date_next'] = 'required';
			$rules['amount'] .= '|max:' . $payment->amount;

			$this->validate($request, $rules);

			$diff = $payment->amount - $request->input('amount', $payment->amount);

			// If amount is not fully paid, a new payment will be created for the difference
			if ( 1 || $diff > 0 ) {
				$new_payment = $payment->replicate( ['id', 'due_date', 'payment_date', 'bank_order_id'] );

				$due_date = $request->input('due_date_next') ?: \Carbon\Carbon::now();

				$new_payment->reference = $payment->reference . ' bounced ';
				$new_payment->name = $payment->name . ' bounced ';
				$new_payment->status = 'pending';
				$new_payment->due_date = $due_date;
				$new_payment->payment_date = NULL;
//				$new_payment->amount = $diff;

				$new_payment->save();

			}

			// 'pending' and / or 'paid' vouchers can be bounced
			$from_status = $payment->status;
//			$payment->name     = $request->input('name',     $payment->name);
//			$payment->due_date = $request->input('due_date', $payment->due_date);
			$payment->payment_date = $request->input('payment_date');
//			$payment->amount   = $request->input('amount',   $payment->amount);
			$payment->notes    = $request->input('notes',    $payment->notes);

			$payment->status   = 'bounced';
			$payment->save();

			// Update Customer Risk
			event(new CustomerPaymentBounced($payment, $from_status));


			return redirect($back_route)
					->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name') . ' / ' . $request->input('due_date'));
		}


		// No action to process
		return redirect($back_route)
				->with('info', l('No action is taken &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name') . ' / ' . $request->input('due_date'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $payment = $this->payment->findOrFail($id);

        if ($payment->amount>0.0)
	        return redirect()->back()
	                ->with('error', l('This record cannot be deleted because its Quantity or Value &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        $payment->delete();

        return redirect()->back()
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
	}




    public function unlink(Request $request, $id)
    {
		$payment = $this->payment->with('bankorder')->findOrFail($id);

		$bankorder = $payment->bankorder;

        $payment->update(['bank_order_id' => null]);

        // Update bankorder
        if ( $bankorder )
        	$bankorder->checkStatus();

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function expressPayVoucher(Request $request, $id)
    {
		$payment = $this->payment->with('bankorder')->findOrFail($id);

        
			$payment->payment_date = \Carbon\Carbon::now();
			$payment->status   = 'paid';

			$payment->save();

			// Update Customer Risk
			event(new CustomerPaymentReceived($payment));



		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function unPayVoucher(Request $request, $id)
    {
		$payment = $this->payment->with('bankorder')->findOrFail($id);

		if ( $payment->status != 'paid' )
		{
			return redirect()->back()
					->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts').' :: '.l('Status is not "paid"'));
		}

        
			$payment->payment_date = null;
			$payment->status   = 'pending';

			$payment->save();

			// Update Customer Risk
			// event(new CustomerPaymentReceived($payment));
			//
			// See: CustomerPaymentReceivedListener
        $document = $payment->customerinvoice;

        // Update Document
        $document->checkPaymentStatus();

        // Update Customer Risk
        $customer = $payment->customer;
        $customer->addRisk($payment->amount);

        // Update bankorder
        if ( $bankorder = $payment->bankorder )
        {
            // Not needed (will be done in event thrown):
            // $bankorder->checkStatus();

            // Voucher is bounced
			$new_payment = $payment->replicate( ['id', 'due_date', 'payment_date', 'bank_order_id'] );

			$due_date = \Carbon\Carbon::now();

			$new_payment->reference = $payment->reference . ' bounced ';
			$new_payment->name = $payment->name . ' bounced ';
			$new_payment->status = 'pending';
			$new_payment->due_date = $due_date;
			$new_payment->payment_date = NULL;
//				$new_payment->amount = $diff;

			$new_payment->save();

//			$payment->name     = $request->input('name',     $payment->name);
//			$payment->due_date = $request->input('due_date', $payment->due_date);
//			$payment->payment_date = $request->input('payment_date');
//			$payment->amount   = $request->input('amount',   $payment->amount);
//			$payment->notes    = $request->input('notes',    $payment->notes);

			$payment->status   = 'bounced';
			$payment->save();

			// Update Customer Risk
			event(new CustomerPaymentBounced($payment));
        }

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


}
