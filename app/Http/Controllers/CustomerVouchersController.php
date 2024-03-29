<?php 

namespace App\Http\Controllers;

use App\Events\CustomerPaymentBounced;
use App\Events\CustomerPaymentReceived;
use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Traits\DateFormFormatterTrait;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use aBillander\SepaSpain\SepaDirectDebit;

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
						->with('chequedetail')
						->with('bankorder')
						->orderBy('due_date', 'desc');		// ->get();

        $payments = $payments->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $payments->setPath('customervouchers');

        $statusList = Payment::getStatusList();

        $payment_typeList = PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray();

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

        return view('customer_vouchers.index', compact('payments', 'statusList', 'payment_typeList', 'sddList'));
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
					->orderBy('due_date', 'desc');

        $payments = $payments->paginate( $items_per_page );

        $payments->setPath($id);

        $statusList = Payment::getStatusList();

        $payment_typeList = PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('customer_vouchers.index_by_customer', compact('customer', 'payments', 'statusList', 'payment_typeList', 'items_per_page'));
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
        $this->addFormDates( ['due_date', 'payment_date'], $payment );
		
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

	// SepaSpain Direct Debit
	public function payVouchers(Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['payment_date'], $request );
		
		// Validate input (to do)
		$rules = [
            'payment_date' => 'required|date',
            'payment_type_id' => 'nullable|exists:payment_types,id',
		];
        $this->validate($request, $rules);

		// $payment_date = ;

		$list = $request->input('vouchers', []);

        if ( count( $list ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts') . l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));


		// abi_r($list);die();
        $payments = $this->payment->whereIn('id', $list)->where('status', 'pending')->get();

        $payment_date = $request->input('payment_date');
        $payment_type_id = $request->input('payment_type_id', null);

        // abi_r($request->all());die();

        // Do the Mambo!
        foreach ( $payments as $payment ) 
        {
        	$payment->payment_date = $payment_date;
        	$payment->payment_type_id = $payment_type_id;

			$payment->status   = 'paid';
			$payment->save();

			// Update Customer Risk
			event(new CustomerPaymentReceived($payment));
        }

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts') .  $request->input('payment_date_form'));
	}


	public function payBulk(Request $request)
	{
        // Dates (cuen)
        $this->mergeFormDates( ['bulk_payment_date'], $request );

        // abi_r($request->all());die();
		
		// Validate input (to do)
		$rules = [
            'bulk_payment_date' => 'required|date',
            'bulk_payment_type_id' => 'nullable|exists:payment_types,id',
		];
        $this->validate($request, $rules);

		// $payment_date = ;

		$list = $request->input('payment_group', []);

        if ( count( $list ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts') . l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));


		// abi_r($list);die();
        $payments = $this->payment->whereIn('id', $list)->where('status', 'pending')->get();

        $payment_date = $request->input('bulk_payment_date');
        $payment_type_id = (int) $request->input('bulk_payment_type_id');

        // abi_r($request->all());die();

        // Do the Mambo!
        foreach ( $payments as $payment ) 
        {
        	$payment->payment_date = $payment_date;
        	
        	// force $payment_type_id
        	if ( $payment_type_id > 0 )
        		$payment->payment_type_id = $payment_type_id;

			$payment->status   = 'paid';
			$payment->save();

			// Update Customer Risk
			event(new CustomerPaymentReceived($payment));
        }

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $payments->count()], 'layouts') .  $request->input('payment_date_form'));
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

			if ( $payment->status == 'pending' )			// Other statuses: only field notes is allowed for update
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

				$new_payment->payment_type_id = $payment->payment_type_id;

				$new_payment->save();

			}

			$payment->name     = $request->input('name',     $payment->name);
			$payment->due_date = $request->input('due_date', $payment->due_date);
			$payment->payment_date = $request->input('payment_date', $payment->payment_date);	// Maybe you want to edit a paid voucher because you made a mistake
			$payment->amount   = $request->input('amount',   $payment->amount);
			$payment->notes    = $request->input('notes',    $payment->notes);

			$payment->auto_direct_debit = $request->input('auto_direct_debit',    $payment->auto_direct_debit);

			$payment->payment_type_id    = $request->input('payment_type_id',    $payment->payment_type_id);

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

				$new_payment->payment_type_id = $payment->payment_type_id;

				$new_payment->save();

			}

			$payment->name     = $request->input('name',     $payment->name);
//			$payment->due_date = $request->input('due_date', $payment->due_date);
			$payment->payment_date = $request->input('payment_date');
			$payment->amount   = $request->input('amount',   $payment->amount);
			$payment->notes    = $request->input('notes',    $payment->notes);

			$payment->payment_type_id    = $request->input('payment_type_id',    $payment->payment_type_id);

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
		
		} else

		if ( $action == 'uncollectible' ) {
			//

			$rules = Payment::$rules;
			if ( $request->input('amount_next', 0.0) ) $rules['due_date_next'] = 'required';
			if ( $payment->amount > 0.0 )
				$rules['amount'] .= '|min:0.0|max:' . $payment->amount;
			else
				$rules['amount'] .= '|max:0.0|min:' . $payment->amount;

			$this->validate($request, $rules);

			// Check bankorder
			if ( $bankorder = $payment->bankorder )
        	{
        		return redirect($back_route)
						->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts') . l('There is a Bank Order with this Voucher: :bo', ['bo' => $bankorder->document_reference]));
        	}

			$payment->name     = $request->input('name',     $payment->name);
			$payment->due_date = $request->input('due_date', $payment->due_date);
//			$payment->amount   = $request->input('amount',   $payment->amount);
			$payment->notes    = $request->input('notes',    $payment->notes);

			$payment->auto_direct_debit = $request->input('auto_direct_debit',    $payment->auto_direct_debit);

			$payment->status = 'uncollectible';

			$payment->save();

			// Update customer unresolved amount
			$payment->customer->addUnresolved($payment->amount);


			return redirect($back_route)
					->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts') . $request->input('name') . ' / ' . 'uncollectible');
		
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

	public function unlinkVouchers(Request $request)
	{
        $bankorder = SepaDirectDebit::findOrFail($request->input('bank_order_id'));

		$list = $request->input('vouchers', []);

        $payments = $this->payment
        				 ->whereIn('id', $list)
        				 ->where('status', 'pending')
        				 ->where('bank_order_id', $bankorder->id)
        				 ->get();

        if ( $payments->count() == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts') . l('No action is taken &#58&#58 (:id) ', ['id' => $bankorder->id], 'layouts'));


        // Do the Mambo!
        foreach ( $payments as $payment ) 
        {
        	$payment->update(['bank_order_id' => null]);
        }

        // Update bankorder
//        if ( $bankorder )
        	$bankorder->checkStatus();

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $bankorder->id], 'layouts'));
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
		$payment = $this->payment
						->with('bankorder')
						->with('chequedetail')
						->findOrFail($id);

		if ( $payment->status != 'paid' )
		{
			return redirect()->back()
					->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts').' :: '.l('Status is not "paid"'));
		}

        

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

			// $payment->payment_date = null; <= Keep payment date as it is bounced now...
			$payment->status   = 'bounced';

        } else {
			$payment->payment_date = null;
			$payment->status   = 'pending';

        }

		$payment->save();

        // Update cheque
        if ( $chequedetail = $payment->chequedetail )
        {
        	// Moved from CustomerPaymentBouncedListener
        	$cheque = $chequedetail->cheque;
        	
        	$chequedetail->delete();

            // Moved from CustomerPaymentBouncedListener
        	$cheque->checkStatus();
        }

		// Update Customer Risk
		event(new CustomerPaymentBounced($payment, 'paid'));

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function collectibleVoucher(Request $request, $id)
    {
		$payment = $this->payment->with('bankorder')->findOrFail($id);

		if ( $payment->status != 'uncollectible' )
		{
			return redirect()->back()
					->with('error', l('Unable to update this record &#58&#58 (:id) ', ['id' => $id], 'layouts').' :: '.l('Status is not "paid"'));
		}

        
		$payment->payment_date = null;
		$payment->status   = 'pending';

		$payment->save();

		// Update customer unresolved amount
		$payment->customer->removeUnresolved($payment->amount);

		return redirect()->back()
				->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }






    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        // abi_r( $request->all(), true );

		$payments = $this->payment
	        			->filter( $request->all() )
						->with('customer')
						->with('customerinvoice')
//						->with('paymentable')
//						->with('paymentable.customer')
//						->with('paymentable.customer.bankaccount')
						->where('payment_type', 'receivable')
						->with('bankorder')
						->orderBy('due_date', 'desc')
                        ->get();

        // Limit number of records
        if ( ($count=$payments->count()) > 1000 )
            return redirect()->back()
                    ->with('error', l('Too many Records for this Query &#58&#58 (:id) ', ['id' => $count], 'layouts'));

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        if ( $request->input('date_from_form') && $request->input('date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('date_from_form') . ' y ' . $request->input('date_to_form');

        } else

        if ( !$request->input('date_from_form') && $request->input('date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('date_to_form');

        } else

        if ( $request->input('date_from_form') && !$request->input('date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('date_from_form');

        } else

        if ( !$request->input('date_from_form') && !$request->input('date_to_form') )
        {
            $ribbon = 'todas';

        }

        if ($request->input('customer_id') > 0)
        	$ribbon2 = '['.$request->input('customer_id').'] ' . $request->input('autocustomer_name');
        else
        	$ribbon2 = 'Todos';

        //

        $auto_direct_debit = $request->input('auto_direct_debit', -1);		// Todos, por defecto!!!
        $ribbon1 = $auto_direct_debit > 0 ? 'Sí' : 'No';
        if ( $auto_direct_debit < 0 ) $ribbon1 = 'Todos';

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Recibos de Clientes -::- '.date('d M Y H:i:s'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];		//, date('d M Y H:i:s')];
        $data[] = ['Fecha de Vencimiento: ' . $ribbon];
        $data[] = ['Estado: ' . $request->input('status')];
        $data[] = ['Cliente: ' . $ribbon2];
        $data[] = ['Remesable: ' . $ribbon1];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $headers = [ 
                    'id', 'document_reference', 'DOCUMENT_DATE', 'customer_id', 'accounting_id', 'CUSTOMER_NAME', 'name', 
                    'due_date', 'payment_date', 'amount', 
                    'payment_type_id', 'PAYMENT_TYPE_NAME', 'auto_direct_debit', 

                    'status', 'currency_id', 'CURRENCY_NAME', 'notes',
        ];

        $data[] = $headers;

        $total_amount = 0.0;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($payments as $payment) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $payment->{$header} ?? '';
            }
//            $row['TAX_NAME']          = $category->tax ? $category->tax->name : '';

            $row['due_date'] = abi_date_short($row['due_date']);

            $row['CURRENCY_NAME'] = optional($payment->currency)->name;
            $row['PAYMENT_TYPE_NAME'] = optional($payment->paymenttype)->name;
            $row['customer_id'] = optional($payment->customer)->id;
            $row['accounting_id'] = optional($payment->customer)->accounting_id;
            $row['CUSTOMER_NAME'] = optional($payment->customer)->name_regular;
            $row['BANK_NAME'] = optional($payment->bank)->name;

            $row['DOCUMENT_DATE'] = abi_date_short(optional($payment->customerinvoice)->document_date);

            if ($payment->auto_direct_debit && $payment->bankorder )
            	$row['auto_direct_debit'] = $payment->bankorder->document_reference;

            $row['payment_date'] = $payment->payment_date ? Date::dateTimeToExcel($payment->payment_date) : '';

            $row['amount'] = (float) $payment->amount;

            $data[] = $row;

            $total_amount += $payment->amount;
        }

        // Totals

        $data[] = [''];
        $data[] = ['', '', '', '', '', '', '', '', 'Total:', $total_amount * 1.0];


        $n = count($data);
        $m = $n - 1;

        $styles = [
            'A8:Q8'    => ['font' => ['bold' => true]],
//            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
            "J$n:J$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2', 'A3:C3', 'A4:C4', 'A5:C5', 'A6:C6'];

        $sheetTitle = 'Recibos de Clientes';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function indexByCustomerPending($id, Request $request)
    {
/*
        // Dates (cuen)
        $this->mergeFormDates( ['date_from', 'date_to'], $request );

        $items_per_page = intval($request->input('items_per_page', Configuration::getInt('DEF_ITEMS_PERPAGE')));
        if ( !($items_per_page >= 0) ) 
            $items_per_page = Configuration::getInt('DEF_ITEMS_PERPAGE');
*/
        $customer = $this->customer->findOrFail($id);

		$payments = $this->payment
                    ->whereHas('customer', function ($query) use ($id) {
                            $query->where('id', $id);
                        })
                    ->doesntHave('chequedetail')	// <= Not assigned to a cheque
//        			->filter( $request->all() )
					->with('paymentable')
					->with('paymentable.customer')
					->with('paymentable.customer.bankaccount')
					->where('payment_type', 'receivable')
					->where('status', 'pending')
					->with('bankorder')
					->orderBy('due_date', 'asc')
					->get();

		// abi_r($payments);

        return view('cheques.index_by_customer_pending', compact('customer', 'payments'));
    }
}
