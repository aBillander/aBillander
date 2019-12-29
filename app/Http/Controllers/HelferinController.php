<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;

use App\Customer;
use App\CustomerOrder;
use App\CustomerShippingSlip;
use App\CustomerInvoice;

use App\Product;
use App\Ecotax;

use Excel;

use App\Http\Controllers\HelferinTraits\HelferinProductReorderTrait;
use App\Traits\DateFormFormatterTrait;

class HelferinController extends Controller
{
   
   use HelferinProductConsumptionTrait;
   use HelferinProductReorderTrait;
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
        
        // Customers that have purchases within filters. Lets see:
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
        $customer->grand_total = 0.0;       // Including taxes && RAEE
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

            $customer->grand_total += $document->total_tax_incl;


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
        $header_names = ['Cliente', '', 'Operaciones', 'Valor', 'Ventas', '%Desc.', 'Coste', '%Rent', 'Beneficio', 'Ranking Vtas. %', 'Ranking Benef. %', 'Ventas con Impuestos'];

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
                $row[] = abi_safe_division( $customer->products_cost + $customer->products_profit, $total ) * 100.0;
                $row[] = abi_safe_division($customer->products_profit, $total_profit) * 100.0;

                $row[] = $customer->grand_total * 1.0;
    
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

                $sheet->getStyle('A4:L4')->applyFromArray([
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
                    'L' => '0.00',

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
        $document_model = $model;
        $document_class = '\App\\'.$document_model;
        $model .= 'Line';
        $class = '\App\\'.$model;
        $table = snake_case(str_plural($model));
        $route = str_replace('_', '', $table);

        // Lets see ecotaxes
        $all_ecotaxes = Ecotax::get()->sortByDesc('amount');
        // $check=collect([]);


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

        $nbr_documents = $document_class::
                          whereHas('lines', function ($query) {

                                $query->where('line_type', 'product');
                                $query->where('ecotax_id', '>', 0);
                        })
                        ->when($date_from, function($query) use ($date_from) {

                                $query->where('document_date', '>=', $date_from.' 00:00:00');
                        })
                        ->when($date_to, function($query) use ($date_to) {

                                $query->where('document_date', '<=', $date_to.' 23:59:59');
                        })
//                        ->orderBy('document_date', 'asc')
                        ->get()
                        ->count();
/*
        foreach ($nbr_documents as $v) {
            # code...
            echo $v->id." &nbsp; ".$v->document_reference."<br />";
        }
            echo " &nbsp; Total Facturas: ".$nbr_documents->count()."<br />";die();
*/

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];
        $data[] = ['Informe de RAEE ('.$nbr_documents.' '.l(str_replace("Line","",$model)).') ' . $ribbon, '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['ID', 'Nombre del Eco-Impuesto', 'Cantidad del Impuesto', 'Unidades', 'Cantidad Total'];

        $data[] = $header_names;

        // Nice! Lets move on and retrieve Document Lines by Ecotax
        $total =  0.0;
        $nbr = 0;
        
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

                        $total_lines = $lines->sum('ecotax_total_amount');
                        $nbr_lines = round($total_lines / $all_ecotax->amount);

                        // Do populate
                        $row = [];
                        $row[] = $all_ecotax->id;
                        $row[] = (string) $all_ecotax->name;
                        $row[] = $all_ecotax->amount * 1.0;
                        $row[] = $nbr_lines * 1.0;
                        $row[] = $total_lines * 1.0;
                        $row[] = $lines->unique('customer_invoice_id')->count();
            
                        $data[] = $row;

                        $total += $total_lines;
                        $nbr   += $nbr_lines;

                        // $check = $check->merge( $lines->unique('customer_invoice_id') );
        }

        // Totals
        $data[] = [''];
        $data[] = ['', '', 'Total:', $nbr, $total ];

        // check
        // $data[] = [''];
        // $data[] = ['', '', 'Total:', $check->unique('customer_invoice_id')->count() ];

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
                    'B' => '@',
                    'C' => '0.00',
                    'D' => '0',
                    'E' => '0.00',

                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("C$m:E$n")->applyFromArray([
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




/* ********************************************************************************************* */ 


/* ********************************************************************************************* */  

 
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
