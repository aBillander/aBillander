<?php

namespace App\Http\Controllers;

use App\Events\CustomerPaymentBounced;
use App\Events\CustomerPaymentReceived;
use App\Helpers\Exports\ArrayExport;
use App\Models\Bank;
use App\Models\Cheque;
use App\Models\ChequeDetail;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Currency;
use App\Traits\DateFormFormatterTrait;
use App\Traits\ModelAttachmentControllerTrait;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ChequesController extends Controller
{
   
   use DateFormFormatterTrait;
   use ModelAttachmentControllerTrait;

   protected $cheque;

   public function __construct(Cheque $cheque)
   {
        $this->cheque = $cheque;
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

        $cheques = $this->cheque
                        ->filter( $request->all() )
                        ->has('customer')
                        ->with('customer')
                        ->with('currency')
                        ->with('bank')
 //                       ->orderBy('due_date', 'desc');
                        ->orderBy('id', 'desc');

        $cheques = $cheques->paginate( Configuration::get('DEF_ITEMS_PERPAGE') );

        $cheques->setPath('cheques');

        // $c = new Cheque();
        // $c->id = 22;

        // $cheques = collect( [$c] );

        // abi_r($c);die();

        $statusList = $this->cheque::getStatusList();

        return view('cheques.index', compact('cheques', 'statusList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statusList = $this->cheque::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();

        return view('cheques.create', compact('statusList', 'currencyList', 'bankList'));
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

        $rules = $this->cheque::$rules;

        $this->validate($request, $rules);

        $cheque = Cheque::create($request->all());

        return redirect()->route('cheques.edit', [$cheque->id])
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $cheque->document_number], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function show(Cheque $cheque)
    {
        //

        // $cheque->checkStatus();

        // abi_r();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $cheque = $this->cheque
                        ->has('customer')
                        ->findOrFail($id);

        $statusList = $this->cheque::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();   

        $customer = $cheque->customer;
        $chequedetails = $cheque->details;     

        // abi_r($bankList);die();

        // Dates (cuen)
        $this->addFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $cheque );

        return view('cheques.edit', compact('cheque', 'chequedetails', 'customer', 'statusList', 'currencyList', 'bankList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cheque $cheque)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $request );

        $old_payment_date = $cheque->payment_date;

        $rules = $this->cheque::$rules;

        $this->validate($request, $rules);

        $cheque->update($request->all());

        // Let's see if we have to change status to 'paid'
        if ( $cheque->payment_date && ($cheque->payment_date != $old_payment_date) )
            return $this->payCheque($cheque->id, $request);

        return redirect()->route('cheques.index')
                ->with('info', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $cheque->document_number], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cheque $cheque)
    {
        $id = $cheque->id;

        $cheque->delete();

        return redirect('cheques')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }



/* ********************************************************************************************* */    



    public function voucherDueDates($id, Request $request)
    {
        $cheque = $this->cheque
                        ->with('vouchers')
                        ->findOrFail($id);

        // Process Vouchers
        foreach ($cheque->vouchers as $voucher) {
            # code...
            $voucher->due_date = $cheque->due_date;

            $voucher->save();
        }

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function payCheque($id, Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['payment_date'], $request );

        $cheque = $this->cheque
                        ->with('vouchers')
                        ->with('currency')
                        ->findOrFail($id);

        // Prevent rounding & machine number representation errors
        $cheque_vouchers = $cheque->currency->round( $cheque->vouchers->sum('amount') );
        $cheque_amount   = $cheque->currency->round( $cheque->amount );

        $balance = $cheque_vouchers - $cheque_amount;

        if(0 && $id==98)
        {
            abi_r($cheque->vouchers->sum('amount').' - '.$cheque->amount.' = '. ($cheque->vouchers->sum('amount')-$cheque->amount) );
            abi_r($cheque_vouchers.' - '.$cheque_amount.' = '.$balance);
            die();
/*
580.17 - 580.170000 = 1.1368683772162E-13
580.17 - 580.17 = 0
*/
        }

        if ( $balance != 0 )
            return redirect()->back()
                ->with('error', l('Unable to close this document because lines do not match &#58&#58 (:id) ', ['id' => $id], 'layouts'));

        // Process Cheque
        $payment_date = $request->input('payment_date') ?: \Carbon\Carbon::now();
        $status = 'paid';

        $cheque->update( compact('payment_date', 'status') );

        // Process Vouchers
        foreach ($cheque->vouchers as $voucher) {
            # code...
            $voucher->payment_date = $payment_date;
            $voucher->status   = 'paid';

            $voucher->save();

            event(new CustomerPaymentReceived($voucher));
        }

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


    public function bounceCheque($id, Request $request)
    {
        $cheque = $this->cheque
                        ->with('chequedetails')
                        ->with('chequedetails.customerpayment')
                        ->findOrFail($id);

        // Process Cheque
        $payment_date = null;
        $status = 'bounced';

        $cheque->update( compact('payment_date', 'status') );

        // Process Vouchers
        foreach ($cheque->chequedetails as $chequedetail) {
            # code...

            $voucher = $chequedetail->customerpayment;
            $voucher->payment_date = null;
            $voucher->status   = 'pending';

            $voucher->save();

            event(new CustomerPaymentBounced($voucher));

            $chequedetail->delete();

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
                ChequeDetail::where('id', '=', $position[0])->update(['line_sort_order' => $position[1]]);
            }
        });

        return response()->json($positions);
    }

    
    public function getDetails($id, Request $request)
    {
        $cheque = $this->cheque
                        ->with('chequedetails')
                        ->findOrFail($id);
        
        $chequedetails = $cheque->chequedetails;

        $open_balance = $cheque->currency->round($cheque->amount - $chequedetails->sum('amount'));

        return view('cheques._panel_details_list', compact('cheque', 'chequedetails', 'open_balance'));
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

        $cheques = $this->cheque
                        ->filter( $request->all() )
                        ->with('customer')
                        ->with('currency')
                        ->with('bank')
                        ->orderBy('due_date', 'desc')
                        ->get();

        // Limit number of records
        if ( ($count=$cheques->count()) > 1000 )
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
        $data[] = ['Cheques de Clientes', '', '', '', '', '', '', '', date('d M Y H:i:s')];
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
        foreach ($cheques as $cheque) {
            // $data[] = $line->toArray();
            $row = [];
            foreach ($headers as $header)
            {
                $row[$header] = $cheque->{$header} ?? '';
            }
//            $row['TAX_NAME']          = $category->tax ? $category->tax->name : '';

            $row['CURRENCY_NAME'] = optional($cheque->currency)->name;
            $row['CUSTOMER_NAME'] = optional($cheque->customer)->name_regular;
            $row['BANK_NAME'] = optional($cheque->bank)->name;

            $row['date_of_issue'] = Date::dateTimeToExcel($cheque->date_of_issue);

            $row['amount'] = (float) $cheque->amount;

            $data[] = $row;

            $total_amount += $cheque->amount;
        }

        // Totals

        $data[] = [''];
        $data[] = ['', '', 'Total:', $total_amount * 1.0];


        $n = count($data);
        $m = $n - 1;

        $styles = [
            'A6:R6'    => ['font' => ['bold' => true]],
            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
            "D$n:D$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2', 'A3:C3', 'A4:C4'];

        $sheetTitle = 'Cheques';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }
}
