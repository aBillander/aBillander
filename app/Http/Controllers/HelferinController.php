<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;

use App\Customer;
use App\CustomerOrder;
use App\CustomerShippingSlip;
use App\CustomerInvoice;

use App\Ecotax;

use Excel;

use App\Traits\DateFormFormatterTrait;

class HelferinController extends Controller
{
   
   use DateFormFormatterTrait;

   protected $models = ['CustomerOrder', 'CustomerShippingSlip', 'CustomerInvoice'];

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
        $models = $this->models;

        $modelList = [];
        foreach ($models as $model) {
            # code...
            $modelList[$model] = l($model);
        }

        $default_model = Configuration::get('RECENT_SALES_CLASS');

        return view('helferin.home', compact('modelList', 'default_model'));
    }


    public function reportSales(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['sales_date_from', 'sales_date_to'], $request );

        $date_from = $request->input('sales_date_from')
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('sales_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('sales_date_to'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('sales_date_to'  ))->endOfDay()
                     : null;

        //             abi_r($date_from.' - '.$date_to);die();

        $customer_id = $request->input('sales_customer_id', null);

        $sales_document_from = $request->input('sales_document_from', null);
        
        $sales_document_to   = $request->input('sales_document_to'  , null);

        $model = $request->input('sales_model', Configuration::get('RECENT_SALES_CLASS'));

        $models = $this->models;
        if ( !in_array($model, $models) )
            $model = Configuration::get('RECENT_SALES_CLASS');
        $class = '\App\\'.$model;
        $table = snake_case(str_plural($model));
        $route = str_replace('_', '', $table);

        $grouped = $request->input('sales_grouped', 1);
        
        // Customers who have purchases within filters. Lets see:
        $customers =  Customer::when($customer_id>0, function ($query) use ($customer_id) {

                            $query->where('id', $customer_id);
                    })
                    ->whereHas($route, function ($query) use ($date_from, $date_to, $sales_document_from, $sales_document_to) {

                            if ( $date_from )
                                $query->where('document_date', '>=', $date_from);

                            if ( $date_to )
                                $query->where('document_date', '<=', $date_to  );


                            if ( (int) $sales_document_from > 0 )
                                $query->where('id', '>=', $sales_document_from);

                            if ( (int) $sales_document_to   > 0 )
                                $query->where('id', '<=', $sales_document_to  );
                    })
                    ->get();


// Nice! Lets move on and retrieve Documents
foreach ($customers as $customer) {
        # code...
        // Initialize
        $customer->model = $model;
        $customer->nbr_documents   = 0.0;
        $customer->products_cost   = 0.0;
        $customer->products_ecotax = 0.0;
        $customer->products_price  = 0.0;
        $customer->products_final_price       = 0.0;
        $customer->document_products_discount = 0.0;
        $customer->products_total = 0.0;
        $customer->products_profit = 0.0;
//        $customer-> = 0.0;

        $documents = $class::where('customer_id', $customer->id)
                    ->where( function ($query) use ($date_from, $date_to, $sales_document_from, $sales_document_to) {

                            if ( $date_from )
                                $query->where('document_date', '>=', $date_from);

                            if ( $date_to )
                                $query->where('document_date', '<=', $date_to  );


                            if ( (int) $sales_document_from > 0 )
                                $query->where('id', '>=', $sales_document_from);

                            if ( (int) $sales_document_to   > 0 )
                                $query->where('id', '<=', $sales_document_to  );
                    } )
                    ->get();

        $customer->nbr_documents = $documents->count();

        // Lets peep under the hood:
        foreach ($documents as $document) {
            # code...
            $document->calculateProfit();

            $customer->products_cost   += $document->products_cost;
            $customer->products_ecotax += $document->products_ecotax;
            $customer->products_price  += $document->products_price;
            $customer->products_final_price       += $document->products_final_price;
            $customer->document_products_discount += $document->document_products_discount;
            $customer->products_total += ($document->products_final_price - $document->products_ecotax - $document->document_products_discount);
            $customer->products_profit += $document->products_profit;


            /*
            abi_r($document->id);
            abi_r($document->products_cost.' - '.$customer->products_cost);
            abi_r($document->products_ecotax.' - '.$customer->products_ecotax);
            abi_r($document->products_price.' - '.$customer->products_price);
            abi_r($document->products_final_price.' - '.$customer->products_final_price);
            abi_r($document->document_products_discount.' - '.$customer->document_products_discount);
            abi_r($document->products_profit.' - '.$customer->products_profit);

            abi_r('*********');
            */
        }


        // abi_r($documents->count());
        // abi_r($customers, true);

}

        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('sales_date_from_form') && $request->input('sales_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('sales_date_from_form') . ' y ' . $request->input('sales_date_to_form');

        } else

        if ( !$request->input('sales_date_from_form') && $request->input('sales_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('sales_date_to_form');

        } else

        if ( $request->input('sales_date_from_form') && !$request->input('sales_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('sales_date_from_form');

        } else

        if ( !$request->input('sales_date_from_form') && !$request->input('sales_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = 'fecha ' . $ribbon;

        $ribbon1  = ( $sales_document_from ? ' desde ' . $sales_document_from : '' );
        $ribbon1 .= ( $sales_document_to   ? ' hasta ' . $sales_document_from : '' );

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Rentabilidad de Clientes ('.l($model).') ' . $ribbon1 . ', y ' . $ribbon, '', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Cliente', '', 'Operaciones', 'Valor', 'Ventas', '%Desc.', 'Coste', '%Rent', 'Beneficio', 'Ranking Vtas. %', 'Ranking Benef. %'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        $total_price = $customers->sum('products_price');
        $total_cost = $customers->sum('products_cost');
        $total_profit = $customers->sum('products_profit');
        $total =  $total_cost + $total_profit;

        foreach ($customers as $customer) 
        {
                $row = [];
                $row[] = (string) $customer->reference_accounting;
                $row[] = $customer->name_regular;
                $row[] = $customer->nbr_documents;
                $row[] = $customer->products_price * 1.0;
                $row[] = $customer->products_total * 1.0;
                $row[] = $customer->products_price != 0.0
                            ? 100.0 * ($customer->products_price - $customer->products_total) / $customer->products_price
                            : 0.0;
                $row[] = $customer->products_cost * 1.0;
                $row[] = \App\Calculator::margin( $customer->products_cost, $customer->products_total, $customer->currency ) * 1.0;
                $row[] = $customer->products_profit * 1.0;
                $row[] = (($customer->products_cost + $customer->products_profit) / $total) * 100.0;
                $row[] = ($customer->products_profit / $total_profit) * 100.0;
    
                $data[] = $row;

        }

        // Totals
        $data[] = [''];
        $data[] = ['', '', 'Total:', $total_price * 1.0, $total * 1.0, 100.0 * ($total_price - $total) / $total_price, $total_cost * 1.00, \App\Calculator::margin( $total_cost, $total, $customer->currency ) * 1.0, $total_profit ];

//        $i = count($data);

        $sheetName = 'Rentabilidad ' . l($model);

        // Generate and return the spreadsheet
        Excel::create('Rentabilidad', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');

                $sheet->getStyle('A4:K4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
                    'C' => '0.00',
                    'D' => '0.00',
                    'E' => '0.00',
                    'F' => '0.00',
                    'G' => '0.00',
                    'H' => '0.00',
                    'I' => '0.00',
                    'J' => '0.00',
                    'K' => '0.00',

                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("C$m:I$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));

    }


    public function reportEcotaxes(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['ecotaxes_date_from', 'ecotaxes_date_to'], $request );

        $date_from = $request->input('ecotaxes_date_from')
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('ecotaxes_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('ecotaxes_date_to'  )
                     ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('ecotaxes_date_to'  ))->endOfDay()
                     : null;

        $model = $request->input('ecotaxes_model', Configuration::get('RECENT_SALES_CLASS'));

        $models = $this->models;
        if ( !in_array($model, $models) )
            $model = Configuration::get('RECENT_SALES_CLASS');
        $model .= 'Line';
        $class = '\App\\'.$model;
        $table = snake_case(str_plural($model));
        $route = str_replace('_', '', $table);

        // Lets see ecotaxes
        $all_ecotaxes = Ecotax::get()->sortByDesc('amount');


        // Lets get dirty!!


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('ecotaxes_date_from_form') && $request->input('ecotaxes_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('ecotaxes_date_from_form') . ' y ' . $request->input('ecotaxes_date_to_form');

        } else

        if ( !$request->input('ecotaxes_date_from_form') && $request->input('ecotaxes_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('ecotaxes_date_to_form');

        } else

        if ( $request->input('ecotaxes_date_from_form') && !$request->input('ecotaxes_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('ecotaxes_date_from_form');

        } else

        if ( !$request->input('ecotaxes_date_from_form') && !$request->input('ecotaxes_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = 'fecha ' . $ribbon;

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Informe de RAEE ('.l(str_replace("Line","",$model)).') ' . $ribbon, '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['ID', 'Nombre del Eco-Impuesto', 'Cantidad del Impuesto', 'Cantidad Total'];

        $data[] = $header_names;

        // Nice! Lets move on and retrieve Document Lines by Ecotax
        $total =  0.0;
        
        foreach ($all_ecotaxes as $all_ecotax) {
            # code...
            $ecotax_id = $all_ecotax->id;

            $lines =  $class::
                          where('line_type', 'product')
                        ->whereHas('ecotax', function ($query) use ($ecotax_id) {

                                if ( (int) $ecotax_id > 0 )
                                    $query->where('id', $ecotax_id);
                        })
                        ->whereHas('document', function ($query) use ($date_from, $date_to) {

                                if ( $date_from )
                                    $query->where('document_date', '>=', $date_from);
                                
                                if ( $date_to )
                                    $query->where('document_date', '<=', $date_to);
                        })
                        ->get();

            // abi_r($lines->toArray());   // die();

                        // Do populate
                        $row = [];
                        $row[] = $all_ecotax->id;
                        $row[] = (string) $all_ecotax->name;
                        $row[] = $all_ecotax->amount * 1.0;
                        $row[] = $total_lines = $lines->sum('ecotax_total_amount') * 1.0;
            
                        $data[] = $row;

                        $total += $total_lines;
        }

        // Totals
        $data[] = [''];
        $data[] = ['', '', 'Total:', $total ];

//        $i = count($data);

        $sheetName = 'Informe RAEE ' . l(str_replace("Line","",$model));

        // Generate and return the spreadsheet
        Excel::create('Informe RAEE', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');

                $sheet->getStyle('A4:D4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'B' => '@',
                    'C' => '0.00',
                    'D' => '0.00',

                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("C$m:D$n")->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));








                     abi_r($data);die();
                     abi_r($date_from.' - '.$date_to.' - '.$class);die();
    }




/* ********************************************************************************************* */ 


/* ********************************************************************************************* */  

 




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


        // Define the Excel spreadsheet headers
        $header_names = ['Número', 'Fecha', 'Cliente', 'Estado', 'Forma de Pago', 'Base', 'IVA', 'Rec', 'Total'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.
        $alltaxes = \App\Tax::get()->sortByDesc('percent');
        $alltax_rules = \App\TaxRule::get();

        $sub_totals = [];
        
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

// abi_r('************************************');
        }

//        die();

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
                $row[] = $payment->customer->reference_accounting . ' ' . $payment->customer->name_fiscal;
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
        $data[] = ['Inventario histórico, hasta el ' . abi_date_short( $date ), '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $headers = [ 'reference', 'name', 'price', 'cost_price' ];

        $header_names = ['Código', 'Nombre', 'Val.Coste', 'Val.Venta', 'Stock'];

        $data[] = $header_names;
        $total_cost = $total_price = 0.0;

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
//                                ->IsActive()
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
