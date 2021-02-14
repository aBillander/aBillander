<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DownPayment;
use App\DownPaymentDetail;
use App\Currency;
use App\Bank;
use App\Configuration;

use App\Events\CustomerPaymentReceived;
use App\Events\CustomerPaymentBounced;

use Excel;

use App\Traits\DateFormFormatterTrait;
use App\Traits\ModelAttachmentControllerTrait;

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
                        ->orderBy('due_date', 'desc');

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
        $statusList = $this->downpayment::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();

        return view('supplier_down_payments.create', compact('statusList', 'currencyList', 'bankList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $request );

        $rules = $this->downpayment::$rules;

        $this->validate($request, $rules);

        $downpayment = DownPayment::create($request->all());

        return redirect()->route('supplier_down_payments.edit', [$downpayment->id])
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $downpayment->document_number], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DownPayment  $downpayment
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
     * @param  \App\DownPayment  $downpayment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $downpayment = $this->downpayment
                        ->has('customer')
                        ->findOrFail($id);

        $statusList = $this->downpayment::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();   

        $customer = $downpayment->customer;
        $downpaymentdetails = $downpayment->details;     

        // abi_r($bankList);die();

        // Dates (cuen)
        $this->addFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $downpayment );

        return view('supplier_down_payments.edit', compact('downpayment', 'downpaymentdetails', 'customer', 'statusList', 'currencyList', 'bankList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DownPayment  $downpayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DownPayment $downpayment)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $request );

        $old_payment_date = $downpayment->payment_date;

        $rules = $this->downpayment::$rules;

        $this->validate($request, $rules);

        $downpayment->update($request->all());

        // Let's see if we have to change status to 'paid'
        if ( $downpayment->payment_date && ($downpayment->payment_date != $old_payment_date) )
            return $this->payDownPayment($downpayment->id, $request);

        return redirect()->route('supplier_down_payments.index')
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $downpayment->document_number], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DownPayment  $downpayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(DownPayment $downpayment)
    {
        $id = $downpayment->id;

        $downpayment->delete();

        return redirect('downpayments')
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

            event(new CustomerPaymentReceived($voucher));
        }

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function bounceDownPayment($id, Request $request)
    {
        $downpayment = $this->downpayment
                        ->with('downpaymentdetails')
                        ->with('downpaymentdetails.customerpayment')
                        ->findOrFail($id);

        // Process DownPayment
        $payment_date = null;
        $status = 'pending';

        $downpayment->update( compact('payment_date', 'status') );

        // Process Vouchers
        foreach ($downpayment->downpaymentdetails as $downpaymentdetail) {
            # code...

            $voucher = $downpaymentdetail->customerpayment;
            $voucher->payment_date = null;
            $voucher->status   = 'pending';

            $voucher->save();

            event(new CustomerPaymentBounced($voucher));

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
                        ->with('downpaymentdetails')
                        ->findOrFail($id);
        
        $downpaymentdetails = $downpayment->downpaymentdetails;

        $open_balance = $downpayment->amount - $downpaymentdetails->sum('amount');

        return view('supplier_down_payments._panel_details_list', compact('downpayment', 'downpaymentdetails', 'open_balance'));
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
                        ->with('customer')
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
        $data[] = [\App\Context::getContext()->company->name_fiscal];
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


        $sheetName = 'Anticipos a Proveedores' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Anticipos_a_Proveedores', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('A3:C3');
                $sheet->mergeCells('A4:C4');
                
                $sheet->getStyle('A6:R6')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
                    'B' => 'dd/mm/yyyy',
//                    'E' => '0.00%',
                    'D' => '0.00',
//                    'F' => '@',
                ));
                
                $n = count($data);
                $m = $n - 1;
                $sheet->getStyle("D$n:D$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs
    }
}
