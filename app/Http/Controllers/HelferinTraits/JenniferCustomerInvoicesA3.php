<?php 

namespace App\Http\Controllers\HelferinTraits;

use Illuminate\Http\Request;

use App\Configuration;

use App\Payment;
use App\Customer;

use Carbon\Carbon;

use Excel;

trait JenniferCustomerInvoicesA3
{

    /**
     * Export Invoices to A3 Accounting.
     * 
     *      $invoices_report_format = 'a3'
     *
     * @return 
     */
    public function reportCustomerInvoicesA3(Request $request)
    {
        // return redirect()->back()
        //        ->with('error', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
        

        // Dates (cuen)
        $this->mergeFormDates( ['invoices_date_from', 'invoices_date_to'], $request );

        $date_from = $request->input('invoices_date_from')
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_from'))
                     : null;
        
        $date_to   = $request->input('invoices_date_to'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_to'  ))
                     : null;
        
        // $invoices_report_format = $request->input('invoices_report_format');
        // Should be:
        $invoices_report_format = 'a3';

        // Customer?
        $customer_id = (int) $request->input('invoices_customer_id', 0);
        if ( $request->input('invoices_autocustomer_name') == '' )
            $customer_id = 0;

        $documents = \App\CustomerInvoice::
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

        $customer_ribbon = 'Clientes';
        if ( $customer_id > 0 && $documents->first() )
            $customer_ribbon = $documents->first()->customer->name_fiscal;

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['A3 Contabilidad :: Facturas de ' . $customer_ribbon . ', ' . $ribbon, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];

        // All Taxes
        $alltaxes = \App\Tax::get()->sortByDesc('percent');
        $alltax_rules = \App\TaxRule::get();


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'NIF', 'ID Contabilidad', 'Cliente', 'Base', 'Tipo '.Configuration::get('CUSTOMER_INVOICE_TAX_LABEL'), Configuration::get('CUSTOMER_INVOICE_TAX_LABEL')];

        $data[] = $header_names;

        $sub_totals = [];

//  $invoices_report_format == 'compact'
//  =>

        foreach ($documents as $document) {
            $row = [];
/*
            $row[] = $document->document_reference;
            $row[] = abi_date_short($document->document_date);
            $row[] = $document->customer->identification;
            $row[] = $document->customer->accounting_id;
            $row[] = $document->customer->name_fiscal;

            $row[] = '';
            $row[] = '';
            $row[] = $document->total_tax_excl * 1.0;
            $row[] = 0.0;
            $row[] = 0.0;
            $row[] = $document->total_tax_incl * 1.0;

            $i = count($data);
            $data[] = $row;
*/
            // Taxes breakout
            $totals = $document->totals( 'accounting' );

            // abi_r($row);abi_r('*****************************');abi_r($totals);die();

            foreach ( $alltaxes as $alltax )
            {
                if ( !( $total = $totals->where('tax_id', $alltax->id)->first() ) ) continue;
                
                $iva = $total['tax_lines']->where('tax_rule_type', 'sales')->first();
                $re  = $total['tax_lines']->where('tax_rule_type', 'sales_equalization')->first();

                $row = [];
                $row[] = $document->document_reference;
                $row[] = abi_date_short($document->document_date);
                $row[] = $document->customer->identification;
                $row[] = $document->customer->accounting_id;
                $row[] = $document->customer->name_fiscal;
                $row[] = $iva->taxable_base * 1.0;
                $row[] = $alltax->percent / 100.0;
                $row[] = $iva->total_line_tax * 1.0;
//                $row[] = optional($re)->total_line_tax ?? 0.0;
//                $row[] = '';
    
                $data[] = $row;
/*
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
*/
                // abi_r($sub_totals);
            }

        }   // Document loop ends here





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

//        $data[] = [''];
//        $data[] = ['', '', '', '', '', '', 'Total:', $base * 1.0, $iva * 1.0, $re * 1.0, ($base + $iva + $re) * 1.0];

        $fileName  = 'Facturas_A3 ' . $request->input('invoices_date_from') . ' ' . $request->input('invoices_date_to');
        $sheetName = 'Facturas A3';

        // Generate and return the spreadsheet
        Excel::create($fileName , function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                
                $sheet->getStyle('A4:U4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
                    'B' => 'dd/mm/yyyy',
//                    'E' => '0.00%',
                    'F' => '0.0000 €',
                    'G' => '0.00%',
                    'H' => '0.0000 €',
//                    'I' => '0.0000',
//                    'J' => '0.0000',
//                    'K' => '0.0000',
//                    'F' => '@',
                ));
/*                
                $n = count($data);
                $m = $n - 3;
                $sheet->getStyle("E$m:K$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
*/
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');





        // abi_r($request->all(), true);


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }



/* ********************************************************************************************* */    


}