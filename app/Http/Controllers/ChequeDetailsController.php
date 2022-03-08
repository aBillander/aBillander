<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\ChequeDetail;
use App\Models\Configuration;
use App\Models\CustomerInvoice;
use App\Models\Configuration;
use App\Models\Payment;
use App\Models\PaymentType;
use Illuminate\Http\Request;

// use App\Events\CustomerPaymentReceived;

class ChequeDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($chequeId)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($chequeId)
    {
        $cheque = Cheque::
                      with('chequedetails')
                    ->with('customer')
                    ->with('currency')
                    ->findOrFail($chequeId);
        
        return view('cheque_details.create', compact('cheque'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($chequeId, Request $request)
    {
        if($request->ajax()){

            return $this->storeAjax($chequeId, $request);

        }

        $cheque = Cheque::with('chequedetails')->findOrFail($chequeId);

        $customer_id = $cheque->customer_id;

        // Customer of Invoice is the same as Customer of Cheque
        $extra_rules = [
            'customer_invoice_id' => [                                                                  
                'nullable',                                                            
                \Illuminate\Validation\Rule::exists('customer_invoices', 'id')                     
                    ->where(function ($query) use ($customer_id) {                      
                        $query->where('customer_id', $customer_id);                  
                }),                                                                    
            ],
        ];

        // Two steps validation :: Validation with ChequeDetail::$rules + $extra_rules won't work
        $this->validate($request, ChequeDetail::$rules);
        $this->validate($request, $extra_rules);

        // Handy conversions
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $cheque->chequedetails->max('line_sort_order') + 10  ] );


        $chequedetail = ChequeDetail::create($request->all());

        $cheque->chequedetails()->save($chequedetail);

        // $cheque->update([
        //         'total_tax_incl' => 0.0,
        //         'total_tax_excl' => 0.0,
        // ]);

        return redirect('cheques/'.$chequeId.'/edit')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $chequedetail->id], 'layouts') . $chequedetail->line_sort_order);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAjax($chequeId, Request $request)
    {
        $cheque = Cheque::with('chequedetails')
                        ->with('customer')
                        ->with('currency')
                        ->findOrFail($chequeId);

        $customer = $cheque->customer;
        
        // Get Customer Voucher IDs 
        $document_group = $request->input('document_group', []);

        if ( count( $document_group ) == 0 )
            return response()->json( [
                'success' => 'OKKO',
                'message' => l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => $chequeId], 'layouts'),
    //            'data' => $customeruser->toArray()
            ] );

        $pay_amount = $request->input('pay_amount', []);

        // Get Customer Vouchers
        $customer_id = $customer->id;
        $vouchers = Payment::
                      whereHas('customer', function ($query) use ($customer_id) {
                            $query->where('id', $customer_id);
                        })
//                  ->filter( $request->all() )
                    ->with('customerinvoice')
                    ->where('payment_type', 'receivable')
                    ->whereIn('id', $document_group)
                    ->get();

        // Check Amounts!
        $detail_pay = [];
        foreach ($vouchers as $voucher) {
            //
            $pay = array_key_exists($voucher->id, $pay_amount) ? 
                    (float) $pay_amount[$voucher->id] : 
                    $voucher->amount;
            
            if ($pay > $voucher->amount) $pay = $voucher->amount;
            if ($pay <= 0.0            ) $pay = $voucher->amount;

            $detail_pay[$voucher->id] = $pay;
        }

//        abi_r($document_group);
//        abi_r($pay_amount);
//        abi_r($detail_pay);die();

        $balance = $cheque->vouchers->sum('amount') + array_sum($detail_pay) - $cheque->amount;
        // abi_r($document_group);
        // abi_r($pay_amount);
        // abi_r($detail_pay);
        // abi_r($cheque->vouchers->sum('amount').' + '.array_sum($pay_amount).' - '.$cheque->amount);

        // abi_r($cheque->vouchers->sum('amount').' + '.$vouchers->sum('amount').' - '.$cheque->amount);die();

        if ( $cheque->currency->round($balance) > 0 )
            return response()->json( [
                'success' => 'KO',
                'message' => l('The Amount of the selected Receipts exceeds the value of the Check &#58&#58 (:id) ', ['id' => $chequeId]),
            ] );

        $next_line_sort_order = $cheque->chequedetails->max('line_sort_order') + 10;

        // Create Cheque Details
        foreach ($vouchers as $voucher) {
            # code...
            // Avoid assign voucher twice
            if ( $cheque->chequedetails->where('payment_id', $voucher->id)->first() )
                continue;

            $detail_amount = $detail_pay[$voucher->id];

            $data = [
                'line_sort_order' => $next_line_sort_order,
                'name' => $voucher->customerinvoice->document_reference.' :: '.abi_date_short($voucher->due_date),
                'amount' => $detail_amount,
                'payment_id' => $voucher->id,
                'customer_invoice_id' => $voucher->customerinvoice->id,
                'customer_invoice_reference' => $voucher->customerinvoice->document_reference,
            ];

            $chequedetail = ChequeDetail::create($data);

            $cheque->chequedetails()->save($chequedetail);

            $next_line_sort_order += 10;

            // Now, set voucher as paid (total or partial)
            $diff = $voucher->amount - $detail_amount;

            // If amount is not fully paid, a new payment will be created for the difference
            if ( $diff != 0 ) {
                $new_payment = $voucher->replicate( ['id', 'due_date', 'payment_date', 'amount'] );

                $due_date = $request->input('due_date_next') ?: \Carbon\Carbon::now();

                $new_payment->name = $voucher->name . ' * ';
                $new_payment->status = 'pending';
                $new_payment->due_date = $due_date;
                $new_payment->payment_date = NULL;
                $new_payment->amount = $diff;

                $new_payment->payment_type_id = $voucher->payment_type_id;

                $new_payment->save();

            }


            $payment_type_id = $request->input('payment_type_id', Configuration::getInt('DEF_CHEQUE_PAYMENT_TYPE'));
            if ( !PaymentType::where('id', $payment_type_id)->exists() )
                $payment_type_id = $voucher->payment_type_id;

            $voucher->name     = $request->input('name',     $voucher->name);
//          $voucher->due_date = $request->input('due_date', $voucher->due_date);
            // $voucher->payment_date = $request->input('payment_date') ?: \Carbon\Carbon::now();
            $voucher->amount   = $detail_amount;
            $voucher->notes    = $request->input('notes',    $voucher->notes);

            $voucher->payment_type_id    = $payment_type_id;

            // $voucher->status   = 'paid';
            $voucher->save();

            // Update Customer Risk
            // event(new CustomerPaymentReceived($voucher));

        }        

        return response()->json( [
            'success' => 'OK',
            'message' => 'OK',
//            'data' => $customeruser->toArray()
        ] );

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function show($chequeId, ChequeDetail $chequeDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($chequeId, ChequeDetail $chequedetail)
    {
        $cheque = Cheque::with('chequedetails')->findOrFail($chequeId);

        
        return view('cheque_details.edit', compact('cheque', 'chequedetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function update($chequeId, Request $request, ChequeDetail $chequedetail)
    {
        $cheque = Cheque::with('chequedetails')->findOrFail($chequeId);

        $customer_id = $cheque->customer_id;

        // Customer of Invoice is the same as Customer of Cheque
        $extra_rules = [
            'customer_invoice_id' => [                                                                  
                'nullable',                                                            
                \Illuminate\Validation\Rule::exists('customer_invoices', 'id')                     
                    ->where(function ($query) use ($customer_id) {                      
                        $query->where('customer_id', $customer_id);                  
                }),                                                                    
            ],
        ];

        // Two steps validation :: Validation with ChequeDetail::$rules + $extra_rules won't work
        $this->validate($request, ChequeDetail::$rules);
        $this->validate($request, $extra_rules);

        // Handy conversions
        if ( !$request->input('line_sort_order') ) 
            $request->merge( ['line_sort_order' => $cheque->chequedetails->max('line_sort_order') + 10  ] );


        $chequedetail->update($request->all());

        // $cheque->update([
        //         'total_tax_incl' => 0.0,
        //         'total_tax_excl' => 0.0,
        // ]);

        return redirect('cheques/'.$chequeId.'/edit')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $chequedetail->id], 'layouts') . $chequedetail->line_sort_order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChequeDetail  $chequeDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($chequeId, ChequeDetail $chequedetail)
    {
        $id = $chequedetail->id;

        $chequedetail->delete();

        return redirect('cheques/'.$chequeId.'/edit')
                ->with('info', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts') . $chequedetail->line_sort_order);
    }



    /*
    |--------------------------------------------------------------------------
    | Ajax Stuff
    |--------------------------------------------------------------------------
    */

    public function searchInvoice($id, Request $request)
    {
        $search = $request->term;

        $invoices = CustomerInvoice::select('id', 'document_reference', 'total_tax_incl')
                                ->where(function($query) use ($search)
                                {
                                    $query->where  ( 'id',                 'LIKE', '%'.$search.'%' );
                                    $query->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' );
                                })
                                ->where('customer_id', $request->input('customer_id'))
                                ->where('currency_id', $request->input('currency_id'))
                                ->where('status', 'closed')
                                ->where('total_tax_incl', '>', 0.0)
//                                ->toSql();
                                ->take( intval(Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $invoices );
    }
}
