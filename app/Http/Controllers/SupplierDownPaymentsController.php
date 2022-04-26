<?php

namespace App\Http\Controllers;

use App\Events\SupplierPaymentBounced;
use App\Events\SupplierPaymentReceived;
use App\Helpers\Exports\ArrayExport;
use App\Models\Bank;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Currency;
use App\Models\DownPayment;
use App\Models\DownPaymentDetail;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\SupplierOrder;
use App\Traits\DateFormFormatterTrait;
use App\Traits\ModelAttachmentControllerTrait;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SupplierDownPaymentsController extends Controller
{
   
   use DateFormFormatterTrait;
   use ModelAttachmentControllerTrait;

   protected $downpayment;

   public function __construct(DownPayment $downpayment)
   {
        $this->downpayment = $downpayment;
   }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue_from', 'date_of_issue_to', 'due_date_from', 'due_date_to'], $request );

        $downpayments = $this->downpayment
                        ->filter( $request->all() )
                        ->has('supplier')
                        ->with('supplier')
                        ->with('currency')
                        ->with('bank')
//                        ->orderBy('due_date', 'desc');
                        ->orderBy('id', 'desc');

        $downpayments = $downpayments->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $downpayments->setPath('supplierdownpayments');

        // $c = new Cheque();
        // $c->id = 22;

        // $downpayments = collect( [$c] );

        // abi_r($c);die();

        $statusList = $this->downpayment::getStatusList();

        return view('supplier_down_payments.index', compact('downpayments', 'statusList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // use createWithDocument() instead!!!
        $statusList = $this->downpayment::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();
        $payment_typeList = PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('supplier_down_payments.create', compact('statusList', 'currencyList', 'bankList', 'payment_typeList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWithDocument($document_id)
    {
        // Do the Mambo!!!
        try {
            $document = SupplierOrder::with('supplier')->with('currency')->findOrFail( $document_id );

        } catch(ModelNotFoundException $e) {
            // No Document_id available, ask for one
            return redirect()->back()
                    ->with('error', l('The record with id=:id does not exist', ['id' => $document_id], 'layouts'));
        }

        // abi_r($document);die();

        $statusList = $this->downpayment::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $currencyList = [$document->currency_id => $document->currency->name];
        $bankList = Bank::pluck('name', 'id')->toArray();
        $payment_typeList = PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        return view('supplier_down_payments.create_with_document', compact('statusList', 'currencyList', 'bankList', 'payment_typeList', 'document'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Do the Mambo!!!
        $document_id = $request->input('supplier_order_id');

        try {
            $document = SupplierOrder::with('supplier')->with('currency')->findOrFail( $document_id );

        } catch(ModelNotFoundException $e) {
            // No Document_id available, ask for one
            return redirect()->back()
                    ->with('error', l('The record with id=:id does not exist', ['id' => $document_id], 'layouts'));
        }

        // Dates (cuen)
        $this->mergeFormDates( ['due_date'], $request );

        $rules = $this->downpayment::$rules;

        $this->validate($request, $rules);

        $downpayment = DownPayment::create($request->all());

        // abi_r($downpayment, true);

        // Create Payment
        $data = [   'payment_type' => 'payable', 
                    'reference' => l('Down Payment', 'supplierdownpayments'), 
                    'name' => l('Document', 'supplierdownpayments').': '.($document->document_reference ? $document->document_reference : $document->id), 
//                          'due_date' => abi_date_short( \Carbon\Carbon::parse( $due_date ), \App\Context::getContext()->language->date_format_lite ), 
                    'due_date' => $downpayment->due_date, 
                    'payment_date' => $downpayment->due_date, 
                    'amount' => $downpayment->amount, 
                    'currency_id' => $downpayment->currency_id,
                    'currency_conversion_rate' => $downpayment->currency_conversion_rate, 
                    'status' => 'paid', 
                    'notes' => null,
//                    'document_reference' => $this->document_reference,

//                    'payment_document_id' => $pmethod->payment_document_id,
//                    'payment_method_id' => $pmethod->id,
//                    'auto_direct_debit' => $pmethod->auto_direct_debit,
                    'is_down_payment' => 1,

                    'payment_type_id' => $downpayment->payment_type_id,
            ];

        $payment = Payment::create($data);
        $document->supplier->payments()->save($payment);


        // Create Down Payment Detail
        $data = [

//            'line_sort_order',
//            'name', 
            'amount' => $downpayment->amount, 
            'payment_id' => $payment->id,
//            'document_invoice_id',
//            'document_invoice_reference',
//            'down_payment_id'

        ];

        $downpaymentdetail = DownPaymentDetail::create($data);

        $downpayment->downpaymentdetails()->save($downpaymentdetail);


        return redirect()->route('supplier.downpayments.edit', [$downpayment->id])
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $downpayment->reference], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DownPayment  $downpayment
     * @return \Illuminate\Http\Response
     */
    public function show(DownPayment $downpayment)
    {
        //

        // $downpayment->checkStatus();

        // abi_r();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DownPayment  $downpayment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $downpayment = $this->downpayment
                        ->has('supplier')
                        ->with('supplier')
                        ->with('supplierorder')
                        ->findOrFail($id);

        $statusList = $this->downpayment::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();   
        $payment_typeList = PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray();

        $supplier = $downpayment->supplier;
        $document = $downpayment->supplierorder;

        // abi_r($downpayment->supplier_order_id);die();
        $downpaymentdetails = $downpayment->details;     

        // abi_r($bankList);die();

        // Dates (cuen)
        $this->addFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $downpayment );

        return view('supplier_down_payments.edit', compact('downpayment', 'downpaymentdetails', 'supplier', 'document', 'statusList', 'currencyList', 'bankList', 'payment_typeList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DownPayment  $downpayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $request );

        $downpayment = $this->downpayment
                        ->has('supplier')
                        ->with('supplier')
                        ->with('supplierorder')
                        ->with('downpaymentdetails')
                        ->with('downpaymentdetails.supplierpayment')
                        ->findOrFail($id);

        $old_payment_date = $downpayment->payment_date;

        $rules = $this->downpayment::$rules;

        $this->validate($request, $rules);

        $downpayment->update($request->all());

        // Update Payment
        $data = [
                    'due_date' => $downpayment->due_date, 
                    'payment_date' => $downpayment->due_date, 
                    'amount' => $downpayment->amount, 
                    'currency_id' => $downpayment->currency_id,
                    'currency_conversion_rate' => $downpayment->currency_conversion_rate, 

                    'payment_type_id' => $downpayment->payment_type_id,
            ];

        // Only one payment
        $downpayment->downpaymentdetails->first()->supplierpayment->update($data);

        return redirect()->back()
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $downpayment->document_number], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DownPayment  $downpayment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $downpayment = $this->downpayment
                        ->has('supplier')
                        ->with('downpaymentdetails')
                        ->with('downpaymentdetails.supplierpayment')
                        ->findOrFail($id);

        // Check if can delete
        if ( !$downpayment->deletable )
            return redirect()->back()
                ->with('success', l('This record cannot be deleted because it is in use &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        foreach ($downpayment->downpaymentdetails as $detail) {
            # code...
            $detail->supplierpayment->delete();
            $detail->delete();
        }

        $downpayment->delete();

        return redirect('supplierdownpayments')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }



/* ********************************************************************************************* */    



    public function payDownPayment($id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['payment_date'], $request );

        $downpayment = $this->downpayment
                        ->with('vouchers')
                        ->findOrFail($id);

        $balance = $downpayment->vouchers->sum('amount') - $downpayment->amount;

        // abi_r($downpayment->vouchers->sum('amount').' + '.$vouchers->sum('amount').' - '.$downpayment->amount);die();

        if ( $balance != 0 )
            return redirect()->back()
                ->with('error', l('Unable to close this document because lines do not match &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        // Process DownPayment
        $payment_date = $request->input('payment_date') ?: \Carbon\Carbon::now();
        $status = 'paid';

        $downpayment->update( compact('payment_date', 'status') );

        // Process Vouchers
        foreach ($downpayment->vouchers as $voucher) {
            # code...
            $voucher->payment_date = $payment_date;
            $voucher->status   = 'paid';

            $voucher->save();

            event(new SupplierPaymentReceived($voucher));
        }

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function bounceDownPayment($id, Request $request)
    {
        $downpayment = $this->downpayment
                        ->with('downpaymentdetails')
                        ->with('downpaymentdetails.supplierpayment')
                        ->findOrFail($id);

        // Process DownPayment
        $payment_date = null;
        $status = 'pending';

        $downpayment->update( compact('payment_date', 'status') );

        // Process Vouchers
        foreach ($downpayment->downpaymentdetails as $downpaymentdetail) {
            # code...

            $voucher = $downpaymentdetail->supplierpayment;
            $voucher->payment_date = null;
            $voucher->status   = 'pending';

            $voucher->save();

            event(new SupplierPaymentBounced($voucher));

            $downpaymentdetail->delete();

        }

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }





/* ********************************************************************************************* */    




    /**
     * AJAX Stuff.
     *
     * 
     */
    public function sortLines(Request $request)
    {
        $positions = $request->input('positions', []);

        \DB::transaction(function () use ($positions) {
            foreach ($positions as $position) {
                DownPaymentDetail::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }

    
    public function getDetails($id, Request $request)
    {
        $downpayment = $this->downpayment
                        ->has('supplier')
                        ->with('downpaymentdetails')
                        ->with('downpaymentdetails.supplierpayment')
                        ->findOrFail($id);
        
        $downpaymentdetails = $downpayment->downpaymentdetails;

        return view('supplier_down_payments._panel_details_list', compact('downpayment', 'downpaymentdetails'));
    }



/* ********************************************************************************************* */    





    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue_from', 'date_of_issue_to', 'due_date_from', 'due_date_to'], $request );

        // abi_r( $request->all(), true );

        $downpayments = $this->downpayment
                        ->filter( $request->all() )
                        ->with('supplier')
                        ->with('currency')
                        ->with('bank')
                        ->orderBy('due_date', 'desc')
                        ->get();

        // Limit number of records
        if ( ($count=$downpayments->count()) > 1000 )
            return redirect()->back()
                    ->with('error', l('Too many Records for this Query &#58&#58 (:id) ', ['id' => $count], 'layouts'));

        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        if ( $request->input('date_of_issue_from_form') && $request->input('date_of_issue_to_form') )
        {
            $ribbon = 'entre ' . $request->input('date_of_issue_from_form') . ' y ' . $request->input('date_of_issue_to_form');

        } else

        if ( !$request->input('date_of_issue_from_form') && $request->input('date_of_issue_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('date_of_issue_to_form');

        } else

        if ( $request->input('date_of_issue_from_form') && !$request->input('date_of_issue_to_form') )
        {
            $ribbon = 'desde ' . $request->input('date_of_issue_from_form');

        } else

        if ( !$request->input('date_of_issue_from_form') && !$request->input('date_of_issue_to_form') )
        {
            $ribbon = 'todas';

        }

        if ( $request->input('date_of_issue_from_form') && $request->input('date_of_issue_to_form') )
        {
            $ribbon = 'entre ' . $request->input('date_of_issue_from_form') . ' y ' . $request->input('date_of_issue_to_form');

        } else

        if ( !$request->input('date_of_issue_from_form') && $request->input('date_of_issue_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('date_of_issue_to_form');

        } else

        if ( $request->input('date_of_issue_from_form') && !$request->input('date_of_issue_to_form') )
        {
            $ribbon = 'desde ' . $request->input('date_of_issue_from_form');

        } else

        if ( !$request->input('date_of_issue_from_form') && !$request->input('date_of_issue_to_form') )
        {
            $ribbon = 'todas';

        }

        //

        if ( $request->input('due_date_from_form') && $request->input('due_date_to_form') )
        {
            $ribbon1 = 'entre ' . $request->input('due_date_from_form') . ' y ' . $request->input('due_date_to_form');

        } else

        if ( !$request->input('due_date_from_form') && $request->input('due_date_to_form') )
        {
            $ribbon1 = 'hasta ' . $request->input('due_date_to_form');

        } else

        if ( $request->input('due_date_from_form') && !$request->input('due_date_to_form') )
        {
            $ribbon1 = 'desde ' . $request->input('due_date_from_form');

        } else

        if ( !$request->input('due_date_from_form') && !$request->input('due_date_to_form') )
        {
            $ribbon1 = 'todas';

        }

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Anticipos a Proveedores', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Fecha de Emisión: ' . $ribbon];
        $data[] = ['Fecha de Vencimiento: ' . $ribbon1];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $headers = [ 
                    'id', 'document_number', 'place_of_issue', 'amount', 
                    'date_of_issue', 'due_date', 'payment_date', 'posted_at', 'date_of_entry', 'memo', 'notes', 
                    'status', 'currency_id', 'CURRENCY_NAME', 'customer_id', 'CUSTOMER_NAME', 'drawee_bank_id', 'BANK_NAME',
        ];

        $data[] = $headers;

        $total_amount = 0.0;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($downpayments as $downpayment) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $downpayment->{$header} ?? '';
            }
//            $row['TAX_NAME']          = $category->tax ? $category->tax->name : '';

            $row['CURRENCY_NAME'] = optional($downpayment->currency)->name;
            $row['CUSTOMER_NAME'] = optional($downpayment->customer)->name_regular;
            $row['BANK_NAME'] = optional($downpayment->bank)->name;

            $row['amount'] = (float) $downpayment->amount;

            $data[] = $row;

            $total_amount += $downpayment->amount;
        }

        // Totals

        $data[] = [''];
        $data[] = ['', '', 'Total:', $total_amount * 1.0];


        $n = count($data);
        $m = $n - 1;

        $styles = [
            'A6:R6'    => ['font' => ['bold' => true]],
//            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
            "D$n:D$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'B' => NumberFormat::FORMAT_TEXT,
//            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2', 'A3:C3', 'A4:C4'];

        $sheetTitle = 'Anticipos a Proveedores';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}
