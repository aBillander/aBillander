<?php 

namespace App\Http\Controllers\HelferinTraits;

use Illuminate\Http\Request;

use App\Configuration;

use App\Payment;
use App\Customer;

use Carbon\Carbon;

use Excel;

trait JenniferCustomersBalance
{

    /**
     * Redirect to Model 347 dashboard.
     *
     * @return 
     */
    public function reportCustomersBalance(Request $request)
    {
        // abi_r($request->all());

        // To do: check date is issued!
        if ( !$request->input( 'balance_date_to_form'  ) )
        {
            $request->merge(['balance_date_to_form' => abi_date_short( Carbon::now() )]);
        }

        // Dates (cuen)
        $this->mergeFormDates( ['balance_date_to'], $request );
        
        $balance_date_to =  $request->input( 'balance_date_to_form'  );

        $date_to   = $request->input( 'balance_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('balance_date_to'  ))->endOfDay()
                     : null;

        // abi_r($request->all(), true);

        // Recibos 
        //  - expedidos antes de la fecha de referencia (<=) y 
        //  - que la fecha de pago es >= que la fecha de referencia

        // Get Vouchers now. Lets see:
        $vouchers = Payment::where('payment_type', 'receivable')       // CUSTOMER vouchers only
// Customer id is 'paymentorable_id'
//                            ->with('paymentable')
//                            ->with('paymentable.customer')
//                            ->with('bankorder')
                            ->with('customerinvoice')
                            ->when($date_to, function($query) use ($date_to) {

                                    // Not this way, man:
                                    // $query->where('created_at', '<=', $date_to);

                                    // Debt is after Invoice date
                                    $query->whereHas('customerinvoice', function ($query) use ($date_to) {
                                        $query->where('document_date', '<=', $date_to);
                                    });


                                    // Debt is until payment
                                    $query->where(function($query) use ($date_to) {
                                        $query->where('payment_date', '>=', $date_to)
                                              ->orWhere('payment_date', null);
                                    });
                            })
//                            ->orderBy('due_date', 'asc')      // No special order required
                            ->get();

// abi_r($vouchers);die();

        // Get Customer id's
        $vouchers = $vouchers->groupBy('paymentorable_id');
        $customer_ids = $vouchers->keys();

        // Get Customers
        $customers = Customer::whereIn('id', $customer_ids)->orderBy('name_fiscal', 'asc')->get();

        // Do calculate balance
        foreach ($customers as $customer) 
        {
            // Get Vouchers for this Customer
            $customer_vouchers = $vouchers->get($customer->id);

            $customer->balance = $customer_vouchers->sum('amount');

            // To do: Should group vouchers by currency
        }

        // abi_r($customers);die();

        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Saldo de Clientes a fecha: ' . $balance_date_to,  '', '', '', date('d M Y H:i:s')];
        $data[] = [''];

        // Define the Excel spreadsheet headers
        $header_names = ['id Cliente', 'id Contabilidad', 'NIF / CIF', 'Cliente', 'Saldo (€)', ];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        // abi_r($customers);die();

        $total = 0.0;

        foreach ($customers as $customer) 
        {
                $row = [];
                $row[] = $customer->id;
                $row[] = $customer->accounting_id;
                $row[] = $customer->identification;
                $row[] = $customer->name_fiscal;
                $row[] = (float) $customer->balance;
    
                $data[] = $row;

                $total += $customer->balance;

        }

        $data[] = [''];
        $data[] = ['', '', '', 'Total:', (float) $total];

        $sheetName = 'Saldo de Clientes';

        $suffix = Carbon::createFromFormat('Y-m-d', $request->input('balance_date_to'  ));

        // Generate and return the spreadsheet
        Excel::create('Saldo de Clientes '.$suffix, function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');

                $sheet->getStyle('A4:E4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
//                    'A' => '@',
//                    'C' => '0.00',
                    'D' => '0.00',

                ));
                
                $n = count($data);
                $m = $n - 3;
                $sheet->getStyle("E$n:E$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');




        // abi_r($customers);die();


        // abi_r($request->all(), true);


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }



/* ********************************************************************************************* */    


}