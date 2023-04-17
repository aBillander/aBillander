<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\CustomerInvoice;
use App\Models\Tax;
use App\Models\TaxRule;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait JenniferInvoicesTrait
{

    public function reportInvoices(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['invoices_date_from', 'invoices_date_to'], $request );

        $date_from = $request->input('invoices_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_from'))
                     : null;
        
        $date_to   = $request->input('invoices_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_to'  ))
                     : null;

        $id_from = $request->input('invoices_id_from')
                     ? (int) $request->input('invoices_id_from')
                     : null;
        
        $id_to   = $request->input('invoices_id_to'  )
                     ? (int) $request->input('invoices_id_to'  )
                     : null;
        
        $invoices_report_format = $request->input('invoices_report_format');

        if ( $invoices_report_format == 'a3' )
        {
            return $this->reportCustomerInvoicesA3( $request );
        }

        // Customer?
        $customer_id = (int) $request->input('invoices_customer_id', 0);
        if ( $request->input('invoices_autocustomer_name') == '' )
            $customer_id = 0;

        $documents = CustomerInvoice::
                              with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->where( function($query) use ($date_from){
                                        if ( $date_from )
                                        $query->where('document_date', '>=', $date_from->startOfDay());
                                } )
                            ->where( function($query) use ($date_to  ){
                                        if ( $date_to   )
                                        $query->where('document_date', '<=', $date_to  ->endOfDay()  );
                                } )
                            ->where( function($query) use ($id_from){
                                        if ( $id_from )
                                        $query->where('id', '>=', $id_from);
                                } )
                            ->where( function($query) use ($id_to  ){
                                        if ( $id_to   )
                                        $query->where('id', '<=', $id_to  );
                                } )
                            ->where( function($query) use ($customer_id){
                                        if ( $customer_id > 0 )
                                        $query->where('customer_id', $customer_id);
                                } )
//                            ->where('document_date', '>=', $date_from->startOfDay())
//                            ->where('document_date', '<=', $date_to  ->endOfDay()  )
                            ->where( function($query){
                                        $query->where(   'status', 'confirmed' );
                                        $query->orWhere( 'status', 'closed'    );
                                } )
                            ->orderBy('document_prefix', 'desc')
                            ->orderBy('document_reference', 'asc')
                            ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('invoices_date_from_form') && $request->input('invoices_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('invoices_date_from_form') . ' y ' . $request->input('invoices_date_to_form');

        } else

        if ( !$request->input('invoices_date_from_form') && $request->input('invoices_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('invoices_date_to_form');

        } else

        if ( $request->input('invoices_date_from_form') && !$request->input('invoices_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('invoices_date_from_form');

        } else

        if ( !$request->input('invoices_date_from_form') && !$request->input('invoices_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon1 = '';

        if ( $request->input('invoices_id_from') )
        {
            $ribbon1 .= ' desde id=' . $request->input('invoices_id_from');

        }

        if ( $request->input('invoices_id_to') )
        {
            $ribbon1 .= ' hasta id=' . $request->input('invoices_id_to');

        }

        $ribbon = $ribbon . ' ; ' . $ribbon1;

        $customer_ribbon = 'Clientes';
        if ( $customer_id > 0 && $documents->first() )
            $customer_ribbon = $documents->first()->customer->name_fiscal;

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Facturas de ' . $customer_ribbon . ', ' . $ribbon, '', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];

        // All Taxes
        $alltaxes = Tax::get()->sortByDesc('percent');
        $alltax_rules = TaxRule::get();


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'NIF', 'ID', 'Cliente', 'Estado', 'Forma de Pago', 'Base', 'IVA', 'Rec', 'Total'];

        // Add more headers
        if ( $invoices_report_format != 'compact')
        foreach ( $alltaxes as $alltax )
        {
            $header_names[] = 'Base IVA '.$alltax->percent;
            $header_names[] = 'IVA '.$alltax->percent;
            $header_names[] = 'RE '.$alltax->equalization_percent;
        }

        $data[] = $header_names;

        $sub_totals = [];

if ( $invoices_report_format == 'compact') {

        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
            $row[] = abi_date_short($document->document_date);
            $row[] = $document->customer->identification;
            $row[] = $document->customer->id;
            $row[] = $document->customer->name_fiscal;
            $row[] = $document->payment_status;
            $row[] = $document->paymentmethod->name;
            $row[] = $document->total_tax_excl * 1.0;
            $row[] = 0.0;
            $row[] = 0.0;
            $row[] = $document->total_tax_incl * 1.0;

            $i = count($data);
            $data[] = $row;

            // Taxes breakout
            $totals = $document->totals();

            foreach ( $alltaxes as $alltax )
            {
                if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) continue;
                
                $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
                $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalization')->first();

                $row = [];
                $row[] = '';
                $row[] = '';
                $row[] = '';
                $row[] = '';
                $row[] = '';
                $row[] = '';
                $row[] = $alltax->percent / 100.0;
                $row[] = $iva->taxable_base * 1.0;
                $row[] = $iva->total_line_tax * 1.0;
                $row[] = optional($re)->total_line_tax ?? 0.0;
                $row[] = '';
    
                $data[] = $row;

                $data[$i][6+2] += $iva->total_line_tax;
                $data[$i][7+2] += optional($re)->total_line_tax ?? 0.0;

                if ( array_key_exists($alltax->id, $sub_totals) )
                {
                    $sub_totals[$alltax->id]['base']    += $iva->taxable_base;
                    $sub_totals[$alltax->id]['iva']     += $iva->total_line_tax;
                    $sub_totals[$alltax->id]['re']      += optional($re)->total_line_tax ?? 0.0;
                } else {
                    $sub_totals[$alltax->id] = [];
                    $sub_totals[$alltax->id]['percent'] = $alltax->percent;
                    $sub_totals[$alltax->id]['base']    = $iva->taxable_base;
                    $sub_totals[$alltax->id]['iva']     = $iva->total_line_tax;
                    $sub_totals[$alltax->id]['re']      = optional($re)->total_line_tax ?? 0.0;
                }

                // abi_r($sub_totals);
            }

        }   // Document loop ends here

} else {

        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
            $row[] = Date::dateTimeToExcel($document->document_date);
            $row[] = $document->customer->identification;
            $row[] = $document->customer->id;
            $row[] = $document->customer->name_fiscal;
            $row[] = $document->payment_status_name;
            $row[] = $document->paymentmethod->name;
            $row[] = $document->total_tax_excl * 1.0;
            $row[] = 0.0;
            $row[] = 0.0;
            $row[] = $document->total_tax_incl * 1.0;

            $i = count($data);
            // $data[] = $row;

            // Taxes breakout
            $totals = $document->totals();

            foreach ( $alltaxes as $alltax )
            {
                if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) 
                {
                    // Empty Group
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';

                    continue;
                }
                
                $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
                $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalization')->first();

                $row[] = $iva->taxable_base * 1.0;
                $row[] = $iva->total_line_tax * 1.0;
                $row[] = optional($re)->total_line_tax ?? 0.0;
    
                // $data[] = $row;

                $row[6+2] += $iva->total_line_tax;
                $row[7+2] += optional($re)->total_line_tax ?? 0.0;

                if ( array_key_exists($alltax->id, $sub_totals) )
                {
                    $sub_totals[$alltax->id]['base']    += $iva->taxable_base;
                    $sub_totals[$alltax->id]['iva']     += $iva->total_line_tax;
                    $sub_totals[$alltax->id]['re']      += optional($re)->total_line_tax ?? 0.0;
                } else {
                    $sub_totals[$alltax->id] = [];
                    $sub_totals[$alltax->id]['percent'] = $alltax->percent;
                    $sub_totals[$alltax->id]['base']    = $iva->taxable_base;
                    $sub_totals[$alltax->id]['iva']     = $iva->total_line_tax;
                    $sub_totals[$alltax->id]['re']      = optional($re)->total_line_tax ?? 0.0;
                }

                // abi_r($sub_totals);
            }

            $data[] = $row;

        }   // Document loop ends here

}



        // Totals
        $data[] = [''];
        $base = $iva = $re = 0.0;
        foreach ($sub_totals as $value) {
            # code...
            $data[] = ['', '', '', '', '', '', $value['percent'] / 100.0, $value['base'] * 1.0, $value['iva'] * 1.0, $value['re'] * 1.0];
            $base += $value['base'];
            $iva += $value['iva'];
            $re += $value['re'];
        }

        $data[] = [''];
        $data[] = ['', '', '', '', '', '', 'Total:', $base * 1.0, $iva * 1.0, $re * 1.0, ($base + $iva + $re) * 1.0];


        $n = count($data);
        $m = $n - 1 - count($sub_totals);

        $styles = [
            'A4:U4'    => ['font' => ['bold' => true]],
            "E$m:K$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,       
            'G' => NumberFormat::FORMAT_PERCENTAGE_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2'];

        $sheetTitle = 'Facturas_' . $request->input('invoices_date_from') . '_' . $request->input('invoices_date_to') . '-' . $request->input('invoices_id_from') . '_' . $request->input('invoices_id_to');

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}