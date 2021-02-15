<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Excel;

// Helferinnen
use App\Http\Controllers\HelferinTraits\JenniferModelo347Trait;

use App\Traits\DateFormFormatterTrait;

class JenniferController extends Controller
{
    use JenniferModelo347Trait;
   
   use DateFormFormatterTrait;

   private $invoices_report_formatList;
   private $valuation_methodList;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');

        $this->invoices_report_formatList = [
                    'compact' => 'Compacto',
                    'loose' => 'Amplio',
        ];

        $this->mod347_claveList = [
                    'A' => 'Proveedores',
                    'B' => 'Clientes',
        ];

        $this->valuation_methodList = [
                    'cost_average_on_date' => 'Precio Medio en la Fecha',
                    'cost_average_current' => 'Precio Medio Actual',
                    'cost_price_on_date' => 'Precio de Coste en la Fecha',
                    'cost_price_current' => 'Precio de Coste Actual',
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $valuation_methodList = $this->valuation_methodList;

        $invoices_report_formatList = $this->invoices_report_formatList;

        $mod347_claveList = $this->mod347_claveList;

        return view('jennifer.home', compact('valuation_methodList', 'invoices_report_formatList', 'mod347_claveList'));
    }


    public function reportInvoices(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['invoices_date_from', 'invoices_date_to'], $request );

        $date_from = $request->input('invoices_date_from')
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_from'))
                     : null;
        
        $date_to   = $request->input('invoices_date_to'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_to'  ))
                     : null;
        
        $invoices_report_format = $request->input('invoices_report_format');

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

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Facturas de Clientes, ' . $ribbon, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];

        // All Taxes
        $alltaxes = \App\Tax::get()->sortByDesc('percent');
        $alltax_rules = \App\TaxRule::get();


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Cliente', 'Estado', 'Forma de Pago', 'Base', 'IVA', 'Rec', 'Total'];

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
            $row[] = $document->customer->reference_accounting . '-' . $document->customer->name_fiscal;
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
                $row[] = $alltax->percent / 100.0;
                $row[] = $iva->taxable_base * 1.0;
                $row[] = $iva->total_line_tax * 1.0;
                $row[] = optional($re)->total_line_tax ?? 0.0;
                $row[] = '';
    
                $data[] = $row;

                $data[$i][6] += $iva->total_line_tax;
                $data[$i][7] += optional($re)->total_line_tax ?? 0.0;

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
            $row[] = abi_date_short($document->document_date);
            $row[] = $document->customer->reference_accounting . '-' . $document->customer->name_fiscal;
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

                $row[6] += $iva->total_line_tax;
                $row[7] += optional($re)->total_line_tax ?? 0.0;

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
            $data[] = ['', '', '', '', $value['percent'] / 100.0, $value['base'] * 1.0, $value['iva'] * 1.0, $value['re'] * 1.0];
            $base += $value['base'];
            $iva += $value['iva'];
            $re += $value['re'];
        }

        $data[] = [''];
        $data[] = ['', '', '', '', 'Total:', $base * 1.0, $iva * 1.0, $re * 1.0, ($base + $iva + $re) * 1.0];

        $sheetName = 'Facturas ' . $request->input('invoices_date_from') . ' ' . $request->input('invoices_date_to');

        // Generate and return the spreadsheet
        Excel::create('Facturas', function($excel) use ($sheetName, $data) {

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
                    'E' => '0.00%',
                    'F' => '0.00',
                    'G' => '0.00',
                    'H' => '0.00',
                    'I' => '0.00',
//                    'F' => '@',
                ));
                
                $n = count($data);
                $m = $n - 3;
                $sheet->getStyle("E$m:I$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');





        // abi_r($request->all(), true);


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }


    public function reportBankOrders(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['bank_order_date_from', 'bank_order_date_to'], $request );

        $date_from = $request->input('bank_order_date_from')
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('bank_order_date_from'))
                     : null;
        
        $date_to   = $request->input('bank_order_date_to'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('bank_order_date_to'  ))
                     : null;

        $bank_order_from = $request->input('bank_order_from', '')
                    ? $request->input('bank_order_from', '') 
                    : 0;
        
        $bank_order_to = $request->input('bank_order_to', '')
                    ? $request->input('bank_order_to', '') 
                    : 0;

        $documents = \aBillander\SepaSpain\SepaDirectDebit::
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
        $data[] = [\App\Context::getContext()->company->name_fiscal];
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
            $row[] = abi_date_short($document->document_date);
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
                $row[] = abi_date_short($payment->due_date);
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

        $sheetName = 'Remesas ' . $request->input('bank_order_from') . ' ' . $request->input('bank_order_to');

        // Generate and return the spreadsheet
        Excel::create('Facturas', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->getStyle('A4:I4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
                    'B' => 'dd/mm/yyyy',
                    'C' => 'dd/mm/yyyy',
                    'G' => '0.00',
//                    'F' => '@',
                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("E$m:G$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');





        // abi_r($request->all(), true);


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }


    public function reportInventory(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['inventory_date_to'], $request );

        $valuation_methodList = $this->valuation_methodList;

        $valuation_method = $request->input('valuation_method');

        if ( !array_key_exists($valuation_method, $valuation_methodList))
            $valuation_method = array_keys($valuation_methodList)[0];     // count($arr) ? array_keys($arr)[0] : null;


        $products = \App\Product::
                            with('measureunit')
//                          ->with('combinations')                                  
                          ->with('category')
                          ->with('tax')
                          ->with('ecotax')
                          ->with('supplier')
                          ->orderBy('reference', 'asc')
                          ->get();


        $date = $request->input('inventory_date_to')
                ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('inventory_date_to'))
                : \Carbon\Carbon::now();

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Inventario histórico, hasta el ' . abi_date_short( $date ), '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Método de Valoración: '.$valuation_methodList[$valuation_method]];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $headers = [ 'reference', 'name', 'price', 'cost_price' ];

        $header_names = ['Código', 'Nombre', 'Precio Medio en la Fecha', 'Precio Medio Actual', 'Precio de Coste en la Fecha', 'Precio de Coste Actual', 'Precio Ultima Compra', 'Stock', 'Valor', 'Valor a Coste Actual', 'Valor a Precio Venta'];

        $data[] = $header_names;
        $total_value = $total_cost_average = $total_cost = $total_price = 0.0;


        // Convert each member of the returned collection into an array,
        // and append it to the data array.
        foreach ($products as $product) {
            // $data[] = $line->toArray();
            // $stock = $product->getStockToDate( $date );
            $arr = $product->getStockToDateFull( $date );
            $stock = $arr['stock'];
            $row = [];
            $row[] = $product->reference;
            $row[] = $product->name;
            $row[] = $arr['movement'] ? (float) $arr['movement']->cost_price_after_movement : '';
            $row[] = (float) $product->cost_average;
            $row[] = $arr['movement'] ? (float) $arr['movement']->product_cost_price : '';
            $row[] = (float) $product->cost_price;
            $row[] = (float) $product->last_purchase_price;

            $row[] = (float) $stock;

            switch ($valuation_method) {
                case 'cost_average_on_date':
                    # code...
                    $thePrice = $arr['movement'] ? $arr['movement']->cost_price_after_movement : '';
                    break;
                
                case 'cost_average_current':
                    # code...
                    $thePrice = $product->cost_average;
                    break;
                
                case 'cost_price_on_date':
                    # code...
                    $thePrice = $arr['movement'] ? $arr['movement']->product_cost_price : '';
                    break;
                
                case 'cost_price_current':
                    # code...
                    $thePrice = $product->cost_price;
                    break;
                
                default:
                    # code...
                    $thePrice = 0.0;
                    break;
            }


            if ( $stock < 0.0 )
                $stock = 0.0;


            $row[] = $stock * (float) $thePrice;
            $row[] = $stock * $product->cost_price;
            $row[] = $stock * $product->price;
/*
            $row[] = '';
            $row[] = $product->cost_price;
            $row[] = $product->price;
*/
//            $row[] = $product->getStockToDateByWarehouse( 1, \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('inventory_date_to')) );

            $data[] = $row;
            // $total_cost_average  += $row[2];
            // $total_cost  += $row[3];
            // $total_price += $row[4];
            $total_value += $row[8];

            // $arr = $product->getStockToDateFull( $date );
            // if ( $arr['stock'] != $arr['stock1'] )
            //     abi_r($product->reference.' - '.$arr['stock'].' - '.$arr['stock1']);
        }

//    die('OK>');

        // Totals
        $data[] = [''];
        // $data[] = ['', 'Total:', $total_cost_average, $total_cost, $total_price, ''];
        $data[] = ['', '', '', '', '', '', '', 'Total:', $total_value, ''];

        $sheetName = 'Inventario';

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Inventario', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
                
                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');

                $sheet->getStyle('A5:K5')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => '0',
                    'C' => '0.00',
                    'D' => '0.00',
                    'E' => '0',
//                    'F' => '@',
//                    'F' => 'yyyy-mm-dd',
                ));
                
                $n = count($data); 
                $sheet->getStyle("B$n:I$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                // https://www.google.com/search?client=ubuntu&channel=fs&q=laravel+excel+format+cell+as+a+number&ie=utf-8&oe=utf-8
                // https://docs.laravel-excel.com/2.1/export/format.html
                // https://docs.laravel-excel.com/2.1/reference-guide/formatting.html
                // https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/

            });

        })->download('xlsx');

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs





        // abi_r($request->all(), true);


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }


/* ********************************************************************************************* */  


    /**
     * AJAX Stuff.
     *
     * 
     */

    /**
     * Return a json list of records matching the provided query
     *
     * @return json
     */
    public function searchCustomer(Request $request)
    {
//        $term  = $request->has('term')  ? $request->input('term')  : null ;
//        $query = $request->has('query') ? $request->input('query') : $term;


        if ($request->has('term'))
        {
            $search = $request->term;

            $customers = \App\Customer::where(   'name_fiscal',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'name_commercial',      'LIKE', '%'.$search.'%' )
                                    ->orWhere( 'identification', 'LIKE', '%'.$search.'%' )
//                                    ->with('currency')
//                                    ->with('addresses')
                                    ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                    ->get();

//            return $customers;
//            return Product::searchByNameAutocomplete($query, $onhand_only);
//            return Product::searchByNameAutocomplete($request->input('query'), $onhand_only);
//            response( $customers );
//            return json_encode( $customers );
            return response()->json( $customers );
        }

        // Otherwise, die silently
        return json_encode( [ 'query' => '', 'suggestions' => [] ] );
        
    }


    public function searchProduct(Request $request)
    {
        $search = $request->term;

        $products = \App\Product::select('id', 'name', 'reference', 'measure_unit_id')
                                ->where(   'name',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference', 'LIKE', '%'.$search.'%' )
//                                ->IsSaleable()
//                                ->qualifyForCustomer( $request->input('customer_id'), $request->input('currency_id') )
//                                ->IsActive()
//                                ->with('measureunit')
//                                ->toSql();
                                ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $products );
    }


    public function searchCustomerOrder(Request $request)
    {
        $search = $request->term;

        $documents = \App\CustomerOrder::select('id', 'document_reference', 'document_date', 'reference_external')
                                ->where(   'id',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference_external', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_date', 'LIKE', '%'.$search.'%' )
                                ->orderBy('document_date', 'DESC')
                                ->orderBy('id', 'ASC')
//                                ->toSql();
                                ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $documents );
    }


    public function searchCustomerShippingSlip(Request $request)
    {
        $search = $request->term;

        $documents = \App\CustomerShippingSlip::select('id', 'document_reference', 'document_date', 'reference_external')
                                ->where(   'id',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference_external', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_date', 'LIKE', '%'.$search.'%' )
                                ->orderBy('document_date', 'DESC')
                                ->orderBy('id', 'ASC')
//                                ->toSql();
                                ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $documents );
    }


    public function searchCustomerInvoice(Request $request)
    {
        $search = $request->term;

        $documents = \App\CustomerInvoice::select('id', 'document_reference', 'document_date', 'reference_external')
                                ->where(   'id',      'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_reference', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'reference_external', 'LIKE', '%'.$search.'%' )
                                ->orWhere( 'document_date', 'LIKE', '%'.$search.'%' )
                                ->orderBy('document_date', 'DESC')
                                ->orderBy('id', 'ASC')
//                                ->toSql();
                                ->take( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) )
                                ->get();


//                                dd($products);

        return response( $documents );
    }
}
