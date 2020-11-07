<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cheque;
use App\ChequeDetail;
use App\Currency;
use App\Bank;
use App\Configuration;

use Excel;

use App\Traits\DateFormFormatterTrait;

class ChequesController extends Controller
{
   
   use DateFormFormatterTrait;

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
                        ->with('customer')
                        ->with('currency')
                        ->with('bank')
                        ->orderBy('due_date', 'desc');

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

        return redirect()->route('cheques.index')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $cheque->document_number], 'layouts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function show(Cheque $cheque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function edit(Cheque $cheque)
    {
        $statusList = $this->cheque::getStatusList();
        $currencyList = Currency::pluck('name', 'id')->toArray();
        $bankList = Bank::pluck('name', 'id')->toArray();   

        $chequedetails = $cheque->details;     

        // abi_r($bankList);die();

        // Dates (cuen)
        $this->addFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $cheque );

        return view('cheques.edit', compact('cheque', 'chequedetails', 'statusList', 'currencyList', 'bankList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cheque $cheque)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['date_of_issue', 'due_date', 'payment_date', 'date_of_entry'], $request );

        $rules = $this->cheque::$rules;

        $this->validate($request, $rules);

        $cheque->update($request->all());

        return redirect()->route('cheques.index')
                ->with('info', l('This record has been successfully created &#58&#58 (:id) ', ['id' => $cheque->document_number], 'layouts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cheque $cheque)
    {
        $id = $cheque->id;

        $cheque->delete();

        return redirect('cheques')
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $id], 'layouts'));
    }


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
        $data[] = [\App\Context::getContext()->company->name_fiscal];
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

            $row['amount'] = (float) $cheque->amount;

            $data[] = $row;

            $total_amount += $cheque->amount;
        }

        // Totals

        $data[] = [''];
        $data[] = ['', '', 'Total:', $total_amount * 1.0];


        $sheetName = 'Cheques' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Cheques', function($excel) use ($sheetName, $data) {

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
