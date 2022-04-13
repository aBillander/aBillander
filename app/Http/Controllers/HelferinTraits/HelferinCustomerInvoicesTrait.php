<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Context;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait HelferinCustomerInvoicesTrait
{

    public function reportCustomerInvoices(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['customer_invoices_date_from', 'customer_invoices_date_to'], $request );

        $date_from = $request->input('customer_invoices_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('customer_invoices_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('customer_invoices_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('customer_invoices_date_to'  ))->endOfDay()
                     : null;

        //             abi_r($date_from.' - '.$date_to);die();

        $customer_id = $request->input('customer_invoices_customer_id', null);

        
        // Get Invoices. Lets see:
        $documents = CustomerInvoice::
                              with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->with('payments')
                            ->when($date_from, function($query) use ($date_from) {

                                    $query->where('document_date', '>=', $date_from.' 00:00:00');
                            })
                            ->when($date_to, function($query) use ($date_to) {

                                    $query->where('document_date', '<=', $date_to.' 23:59:59');
                            })
                            ->where( function($query){
                                        $query->where(   'status', 'confirmed' );
                                        $query->orWhere( 'status', 'closed'    );
                                } )
                            ->orderBy('document_prefix', 'desc')
                            ->orderBy('document_reference', 'asc')
//                            ->orderBy('document_date', 'asc')
                            ->get();
        
        // https://github.com/Maatwebsite/Laravel-Excel/issues/2161



        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('customer_invoices_date_from_form') && $request->input('customer_invoices_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('customer_invoices_date_from_form') . ' y ' . $request->input('customer_invoices_date_to_form');

        } else

        if ( !$request->input('customer_invoices_date_from_form') && $request->input('customer_invoices_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('customer_invoices_date_to_form');

        } else

        if ( $request->input('customer_invoices_date_from_form') && !$request->input('customer_invoices_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('customer_invoices_date_from_form');

        } else

        if ( !$request->input('customer_invoices_date_from_form') && !$request->input('customer_invoices_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = ':: fecha(s) ' . $ribbon;

        $customer_label = (int) $customer_id > 0
        				? Customer::findOrFail($customer_id)->name_regular
        				: '';

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Facturas de Clientes ' . $ribbon, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];
//        $data[] = ['Cliente:', $customer_label];
//        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Cliente', 'Estado', 'Forma de Pago', 'Total', 'Vencimiento', '', 'Cobrado', '', 'id Cliente', 'id Contabilidad Cliente'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        foreach ($documents as $document) 
        {
                $row = [];
                $row[] = $document->document_reference;
                $row[] = abi_date_short($document->document_date);
                $row[] = '['.$document->customer->id.'] ' . $document->customer->name_fiscal;
                $row[] = $document->payment_status;
                $row[] = $document->paymentmethod->name;
                $row[] = (float) $document->total_tax_incl;
                if ( $document->next_payment )
                {
                    $row[] = abi_date_short($document->next_payment->due_date);
                    $row[] = (float) $document->next_payment->amount;
                } else {
                    $row[] = '';
                    $row[] = '';
                }
                $row[] = $document->payment_date ? abi_date_short($document->payment_date) : '';

                $row[] = '';
                $row[] = $document->customer->id ?? '';
                $row[] = $document->customer->accounting_id ?? '';
    
                $data[] = $row;

        }


        $styles = [
            'A4:L4'    => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2'];

        $sheetTitle = 'Facturas_' . $request->input('customer_invoices_date_from') . '_' . $request->input('customer_invoices_date_to');

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}