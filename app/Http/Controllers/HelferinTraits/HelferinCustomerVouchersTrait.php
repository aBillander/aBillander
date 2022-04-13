<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Context;
use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait HelferinCustomerVouchersTrait
{

    public function reportCustomerVouchers(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['customer_vouchers_date_from', 'customer_vouchers_date_to'], $request );

        $date_from = $request->input('customer_vouchers_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('customer_vouchers_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('customer_vouchers_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('customer_vouchers_date_to'  ))->endOfDay()
                     : null;

        //             abi_r($date_from.' - '.$date_to);die();

        $customer_id = $request->input('customer_vouchers_customer_id', null);

        
        // Get Vouchers. Lets see:
        $vouchers = Payment::where('payment_type', 'receivable')       // CUSTOMER vouchers only
                            ->with('paymentable')
                            ->with('paymentable.customer')
//                            ->with('bankorder')
                            ->when($date_from, function($query) use ($date_from) {

                                    $query->where('due_date', '>=', $date_from.' 00:00:00');
                            })
                            ->when($date_to, function($query) use ($date_to) {

                                    $query->where('due_date', '<=', $date_to.' 23:59:59');
                            })
                            ->orderBy('due_date', 'asc')
                            ->get();
        
        // https://github.com/Maatwebsite/Laravel-Excel/issues/2161



        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('customer_vouchers_date_from_form') && $request->input('customer_vouchers_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('customer_vouchers_date_from_form') . ' y ' . $request->input('customer_vouchers_date_to_form');

        } else

        if ( !$request->input('customer_vouchers_date_from_form') && $request->input('customer_vouchers_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('customer_vouchers_date_to_form');

        } else

        if ( $request->input('customer_vouchers_date_from_form') && !$request->input('customer_vouchers_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('customer_vouchers_date_from_form');

        } else

        if ( !$request->input('customer_vouchers_date_from_form') && !$request->input('customer_vouchers_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = 'fecha ' . $ribbon;

        $customer_label = (int) $customer_id > 0
        				? Customer::findOrFail($customer_id)->name_regular
        				: '';

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Recibos de Clientes ' . $ribbon, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];
//        $data[] = ['Cliente:', $customer_label];
//        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Factura', 'Cliente', 'Concepto', 'Fecha Vencimiento', 'Fecha de Pago', 'Cantidad', 'Remesable (1=si)', 'Estado', 'Remesado (1=si)', '', 'id Cliente', 'id Contabilidad Cliente'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        foreach ($vouchers as $payment) 
        {
                $row = [];
                $row[] = $payment->customerInvoice->document_reference ?? '';
                $row[] = '['.($payment->customerInvoice->customer->id ?? '').'] ' . ($payment->customerInvoice->customer->name_regular ?? '');
                $row[] = $payment->name;
                $row[] = abi_date_short($payment->due_date);
                $row[] = abi_date_short($payment->payment_date);
                $row[] = (float) $payment->amount;
                $row[] = $payment->auto_direct_debit > 0 ? 1 : 0;
                $row[] = $payment->status_name;
                $row[] = $payment->bank_order_id > 0 ? 1 : 0;

                $row[] = '';
                $row[] = $payment->customerInvoice->customer->id ?? '';
                $row[] = $payment->customerInvoice->customer->accounting_id ?? '';
    
                $data[] = $row;

        }



        $styles = [
            'A4:L4'    => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2'];

        $sheetTitle = 'Recibos de Clientes';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}