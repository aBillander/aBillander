<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use aBillander\SepaSpain\SepaDirectDebit;

trait JenniferBankOrdersTrait
{

    public function reportBankOrders(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['bank_order_date_from', 'bank_order_date_to'], $request );

        $date_from = $request->input('bank_order_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('bank_order_date_from'))
                     : null;
        
        $date_to   = $request->input('bank_order_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('bank_order_date_to'  ))
                     : null;

        $bank_order_from = $request->input('bank_order_from', '')
                    ? $request->input('bank_order_from', '') 
                    : 0;
        
        $bank_order_to = $request->input('bank_order_to', '')
                    ? $request->input('bank_order_to', '') 
                    : 0;

        $documents = SepaDirectDebit::
                              with('bankaccount')
 //                           ->with('currency')
                            ->with('vouchers')
                            ->with('vouchers.customer')
                            ->with('vouchers.customerinvoice')
                            ->where( function($query) use ($date_from){
                                        if ( $date_from )
                                        $query->where('document_date', '>=', $date_from->startOfDay());
                                } )
                            ->where( function($query) use ($date_to  ){
                                        if ( $date_to   )
                                        $query->where('document_date', '<=', $date_to  ->endOfDay()  );
                                } )
                            ->where( function($query) use ($bank_order_from){
                                        if ( $bank_order_from > 0 )
                                        $query->where('document_reference', '>=', $bank_order_from);
                                } )
                            ->where( function($query) use ($bank_order_to  ){
                                        if ( $bank_order_to   )
                                        $query->where('document_reference', '<=', $bank_order_to  );
                                } )
//                            ->where('document_date', '>=', $date_from->startOfDay())
//                            ->where('document_date', '<=', $date_to  ->endOfDay()  )
                            ->where( function($query){
                                        $query->where(   'status', 'confirmed' );
                                        $query->orWhere( 'status', 'closed'    );
                                } )
                            ->orderBy('document_date', 'asc')
                            ->orderBy('id', 'asc')
                            ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('bank_order_date_from_form') && $request->input('bank_order_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('bank_order_date_from_form') . ' y ' . $request->input('bank_order_date_to_form');

        } else

        if ( !$request->input('bank_order_date_from_form') && $request->input('bank_order_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('bank_order_date_to_form');

        } else

        if ( $request->input('bank_order_date_from_form') && !$request->input('bank_order_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('bank_order_date_from_form');

        } else

        if ( !$request->input('bank_order_date_from_form') && !$request->input('bank_order_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = 'fecha de expedición ' . $ribbon;

        $ribbon1  = ( $bank_order_from ? ' desde ' . $bank_order_from : '' );
        $ribbon1 .= ( $bank_order_to   ? ' hasta ' . $bank_order_from : '' );

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Remesas de Clientes' . $ribbon1 . ', y ' . $ribbon, '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Fecha Vto.', 'Banco / Cliente', 'Factura', 'Estado', 'Importe', 'Norma'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        $total = 0.0;
        
        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
            $row[] = Date::dateTimeToExcel($document->document_date);
            $row[] = '';
//            $row[] = $document->customer->reference_external . '-' . $document->customer->name_fiscal;
            $row[] = optional($document->bankaccount)->bank_name;
            $row[] = '';
            $row[] = '';
            $row[] = $document->total * 1.0;
            $row[] = $document->scheme;

            $total += $document->total;

            $data[] = $row;

            foreach ( $document->vouchers as $payment )
            {
                $row = [];
                $row[] = '';
                $row[] = '';
                $row[] = Date::dateTimeToExcel($payment->due_date);
                $row[] = $payment->customer->reference_accounting . ' ' . $payment->customer->name_fiscal;;
                $row[] = $payment->customerinvoice->document_reference;
                $row[] = $payment->status;
                $row[] = $payment->amount * 1.0;
                $row[] = '';
    
                $data[] = $row;

                // abi_r($sub_totals);
            }

// abi_r('************************************');
        }

//        die();

        // Totals
        $data[] = [''];
        $data[] = ['', '', '', '', 'Total:', '', $total * 1.0];

//        $i = count($data);


        $n = count($data);
        $m = $n;    //  - 3;
        
        $styles = [
            'A4:I4'    => ['font' => ['bold' => true]],
            "E$m:G$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'B' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = [];

        $sheetTitle = 'Remesas_' . $request->input('bank_order_from') . '_' . $request->input('bank_order_to');

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}