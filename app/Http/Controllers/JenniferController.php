<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Excel;

use App\Traits\DateFormFormatterTrait;

class JenniferController extends Controller
{
   
   use DateFormFormatterTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('jennifer.home');
    }


    public function reportInvoices(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['invoices_date_from', 'invoices_date_to'], $request );

        $date_from = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_from'));
        $date_to   = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('invoices_date_to'  ));

        $documents = \App\CustomerInvoice::
                              with('customer')
                            ->with('currency')
                            ->with('paymentmethod')
                            ->where('document_date', '>=', $date_from->startOfDay())
                            ->where('document_date', '<=', $date_to  ->endOfDay()  )
                            ->where( function($query){
                                        $query->where(   'status', 'confirmed' );
                                        $query->orWhere( 'status', 'closed'    );
                                } )
                            ->orderBy('document_prefix', 'desc')
                            ->orderBy('document_reference', 'desc')
                            ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Facturas de Clientes, entre ' . $request->input('invoices_date_from_form') . ' y ' . $request->input('invoices_date_to_form'), '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Cliente', 'Estado', 'Forma de Pago', 'Base', 'IVA', 'Rec', 'Total'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.
        $alltaxes = \App\Tax::get()->sortByDesc('percent');
        $alltax_rules = \App\TaxRule::get();
        
        foreach ($documents as $document) {
            $row = [];
            $row[] = $document->document_reference;
            $row[] = abi_date_short($document->document_date);
            $row[] = $document->customer->reference_external . '-' . $document->customer->name_fiscal;
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
            $sub_totals = [];

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
            }


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

        $sheetName = 'Facturas ' . $request->input('inventory_date_to');

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
        $this->mergeFormDates( ['invoices_date_from', 'invoices_date_to'], $request );

        abi_r($request->all(), true);


        return view('jennifer.home');
    }


    public function reportInventory(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['inventory_date_to'], $request );


        $products = \App\Product::
                            with('measureunit')
//                          ->with('combinations')                                  
                          ->with('category')
                          ->with('tax')
                          ->with('ecotax')
                          ->with('supplier')
                          ->orderBy('reference', 'asc')
                          ->get();

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Inventario histórico, hasta el ' . $request->input('inventory_date_to_form'), '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $headers = [ 'reference', 'name', 'price', 'cost_price' ];

        $header_names = ['Código', 'Nombre', 'Val.Coste', 'Val.Venta', 'Stock'];

        $data[] = $header_names;
        $total_cost = $total_price = 0.0;

        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('inventory_date_to'));

        // Convert each member of the returned collection into an array,
        // and append it to the data array.
        foreach ($products as $product) {
            // $data[] = $line->toArray();
            $stock = $product->getStockToDate( $date );
            $row = [];
            $row[] = $product->reference;
            $row[] = $product->name;
            $row[] = $stock * $product->cost_price;
            $row[] = $stock * $product->price;
            $row[] = $stock;
/*
            $row[] = '';
            $row[] = $product->cost_price;
            $row[] = $product->price;
*/
//            $row[] = $product->getStockToDateByWarehouse( 1, \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('inventory_date_to')) );

            $data[] = $row;
            $total_cost  += $row[2];
            $total_price += $row[3];
        }

        // Totals
        $data[] = [''];
        $data[] = ['', 'Total:', $total_cost, $total_price, ''];

        $sheetName = 'Inventario a ' . $request->input('inventory_date_to');

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
                
                $sheet->getStyle('A4:F4')->applyFromArray([
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
                $sheet->getStyle("B$n:D$n")->applyFromArray([
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
//                                ->with('measureunit')
//                                ->toSql();
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


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
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


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
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


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
                                ->get( intval(\App\Configuration::get('DEF_ITEMS_PERAJAX')) );


//                                dd($products);

        return response( $documents );
    }
}
