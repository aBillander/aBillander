<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait JenniferCustomersBalanceTrait
{

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

        // Customer?
        $customer_id = (int) $request->input('balance_customer_id', 0);
        if ( $request->input('balance_autocustomer_name') == '' )
            $customer_id = 0;

        // abi_r($request->all()+['cid' => $customer_id], true);

        // Recibos 
        //  - expedidos antes de la fecha de referencia (<=) y 
        //  - que la fecha de pago es >= que la fecha de referencia
        //  - y que no están devueltos

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
                            ->where('status', '<>', 'bounced')
                            ->when($customer_id, function($query) use ($customer_id) {
                                $query->where('paymentorable_id', $customer_id);
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
        $data[] = [Context::getContext()->company->name_fiscal];
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
        $n1 = count($data);
        $data[] = [''];


// Bonus stuff
if( $customer_id > 0 ){
        // Output customer vouchers
        $data[] = ['Desglose de Recibos'];
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

        // abi_r($vouchers);
        // abi_r($headers);

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        $customer_vouchers = $vouchers->get($customer_id);
        if ($customer_vouchers)
        foreach ($customer_vouchers as $payment) {
            // $data[] = $line->toArray();
            // abi_r($payment, true);
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

            $row['amount'] = (float) $payment->amount;

            $data[] = $row;

            $total_amount += $payment->amount;
        }

        // Totals

        $data[] = [''];
        $data[] = ['', '', '', '', '', '', '', '', 'Total:', $total_amount * 1.0];

}


        $styles = [
            'A4:E4'    => ['font' => ['bold' => true]],
        ];

        $n = $n1;
        $styles[ "D$n:E$n" ] = ['font' => ['bold' => true]];

        $n = $n1+2;
        $styles[ "A$n:D$n" ] = ['font' => ['bold' => true]];

        $merges = ['A1:C1', 'A2:C2', "A$n:C$n"];

        $n = $n1+4;
        $styles[ "A$n:Q$n" ] = ['font' => ['bold' => true]];

        $n = count($data);
        $m = $n - 3;
        $styles[ "I$n:J$n" ] = ['font' => ['bold' => true]];


        $columnFormats = [
//            'B' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//            'D' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $suffix = Carbon::createFromFormat('Y-m-d', $request->input('balance_date_to'  ));
        $sheetTitle = 'Saldo de Clientes '.$suffix;

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }



/* ********************************************************************************************* */    


}