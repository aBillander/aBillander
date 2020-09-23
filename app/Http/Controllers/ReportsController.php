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

use App\Tools;

use Carbon\Carbon;

use Excel;

// Helferinnen
use App\Http\Controllers\HelferinTraits\HelferinProductConsumptionTrait;
use App\Http\Controllers\HelferinTraits\HelferinProductReorderTrait;
use App\Http\Controllers\HelferinTraits\HelferinCustomerVouchersTrait;
use App\Http\Controllers\HelferinTraits\HelferinCustomerInvoicesTrait;

use App\Traits\DateFormFormatterTrait;

class ReportsController extends Controller
{
   
   use HelferinProductConsumptionTrait;
   use HelferinProductReorderTrait;
   use HelferinCustomerVouchersTrait;
   use HelferinCustomerInvoicesTrait;

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

        // https://laracasts.com/discuss/channels/laravel/how-to-compile-a-blade-template-from-any-folder-other-than-resourcesviews
        // view()->addNamespace('theme', resource_path('theme'));

/*

// get view factory, eg.: view() / View:: / app('view')

// let's add /app/custom_views via namespace
view()->addNamespace('my_views', app_path('custom_views'));
// then:
view('my_views::some.view.name') // /app/custom_views/some/view/name.blade.php

// OR via path
view()->addLocation(app_path('cutom_views'));
// then:
view('some.view.name') // search in /app/views first, then custom locations

*/
        $selectorMonthList = Tools::selectorMonthList();

        $current = [];
        $current['month'] = Carbon::now()->month;       //  Or: now()->month || Or: (int) Carbon::now()->format('m'); (Carbon extends PHP date format)
        $current['year'] = Carbon::now()->year;

        $selectorNumberYearsList = [0 => 0, 1 => 1, 2 => 2, 3 => 3];

        return view('reports.home', compact('modelList', 'default_model', 'selectorMonthList', 'current', 'selectorNumberYearsList'));
    }


    public function reportProductSales(Request $request)
    {
        // abi_r(Carbon::now()->month);die();

        $product_sales_month_from = $request->input('product_sales_month_from', 1);
        
        $product_sales_month_to   = $request->input('product_sales_month_to', Carbon::now()->month );

        $nbr_years = $request->input('product_sales_years_to_compare', 1);

        $document_total_tax = $request->input('product_sales_value', 'total_tax_incl');

        if ( !in_array($document_total_tax, ['total_tax_incl', 'total_tax_excl']) )
            $document_total_tax = 'total_tax_incl';
 
        $model = $request->input('sales_model', Configuration::get('RECENT_SALES_CLASS'));

        // calculate dates
        // http://zetcode.com/php/carbon/
        $years = [];
        // Current year
        $current_year = Carbon::now()->year;
        $first_year = Carbon::now()->year - $nbr_years;
        $month_from = $product_sales_month_from;
        $month_to   = $product_sales_month_to;
        $date_from = Carbon::create($first_year, $month_from, 1)->startOfDay();
        $date_to   = Carbon::create($first_year, $month_to  , 1)->endOfMonth()->endOfDay();

        $list_of_years = [];
        for ($i=0; $i <= $nbr_years; $i++) { 
            # code...
            $list_of_years[] = $first_year + $i;
        }

        // abi_r($list_of_years, true);

        // abi_r($date_from);
        // abi_r($date_to, true);


        $models = $this->models;
        if ( !in_array($model, $models) )
            $model = Configuration::get('RECENT_SALES_CLASS');
        $class = '\App\\'.$model.'Line';
        $table = snake_case(str_plural($model));
        $route = str_replace('_', '', $table);

        $selectorMonthList = Tools::selectorMonthList();

        $document_reference_date = 'close_date';        // 'document_date'

        $is_invoiceable_flag = ($model == 'CustomerShippingSlip') ? true : false;

        // Wanna dance, Honey Bunny?

        
        // All Products. Lets see:
        $products = Product::select('id', 'name', 'reference')  // , 'measure_unit_id')
//                            ->with('measureunit')
                            ->orderBy('reference', 'asc')
//                            ->take(4)
                            ->get();

        // abi_r($products->count(), true);

$k=0;
// Nice! Lets move on and retrieve Documents
foreach ($products as $product) {
        # code...
        // Initialize
//        $product-> = 0.0;

    $product_date_from = $date_from->copy();
    $product_date_to   = $date_to->copy();

    foreach ($list_of_years as $year) {

        // abi_r($product->name);
        // abi_r($product_date_from);
        // abi_r($product_date_to);

        $product->{$year} = $class::
                          where('line_type', 'product')
                        ->where('product_id', $product->id)
                        ->whereHas('document', function ($query) use ( $product_date_from, $product_date_to, $document_reference_date, $is_invoiceable_flag) {

                                // Closed Documents only
                                $query->where($document_reference_date, '!=', null);

                                // Only invoiceable Documents when Documents are Customer Shipping Slips
                                if ( $is_invoiceable_flag )
                                    $query->where('is_invoiceable', '>', 0);

                                if ( $product_date_from )
                                    $query->where($document_reference_date, '>=', $product_date_from);
                                
                                if ( $product_date_to )
                                    $query->where($document_reference_date, '<=', $product_date_to);
                        })
                        ->sum($document_total_tax);

        $product_date_from->addYear();
        $product_date_to->addYear();

    }
    $k++;
    // if ($k==4) break;
}
// die();
// abi_r($products, true);
        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];

        $row = [];
        $row[] = 'Listado de Ventas comparativas por Producto ('.l($model).') ';
        foreach ($list_of_years as $year) {
            // $row[] = '';
        }
        $row[] = '';
        $row[] = date('d M Y H:i:s');
        $data[] = $row;

        $ribbon = $document_total_tax == 'total_tax_incl' ?
                                            'Ventas son con Impuestos incluidos.' :
                                            'Ventas son sin Impuestos.';
        // $data[] = [ 'Listado de Ventas comparativas por Producto ('.l($model).') ', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Meses: desde '.$selectorMonthList[$product_sales_month_from].' hasta '.$selectorMonthList[$product_sales_month_to].'. '.$ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Referencia', 'Nombre', ];
        foreach ($list_of_years as $year) {
            $header_names[] = $year;
        }

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        // Initialize (colmn) totals
        $totals = [];
        foreach ($list_of_years as $year) {
            $totals[$year] = 0.0;
        }

        foreach ($products as $product) 
        {
                $row = [];
                $row[] = (string) $product->reference;
                $row[] = $product->name;

                foreach ($list_of_years as $year) {
                    $row[] = (float) $product->{$year};

                    $totals[$year] += (float) $product->{$year};
                }
    
                $data[] = $row;

        }

        // Totals
        $data[] = [''];

        $row = [];
        $row[] = '';
        $row[] = 'Total:';
        foreach ($list_of_years as $year) {
            $row[] = (float) $totals[$year];
        }
        $data[] = $row;

        // abi_r($data, true);

//        $i = count($data);

        $sheetName = 'Ventas ' . l($model);

        // Generate and return the spreadsheet
        Excel::create('Ventas', function($excel) use ($sheetName, $data, $nbr_years) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data, $nbr_years) {
                
                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');
                $sheet->mergeCells('C2:'.chr(ord('C') + $nbr_years).'2');   // https://stackoverflow.com/questions/39314048/increment-letters-like-number-by-certain-value-in-php
                $sheet->mergeCells('A3:B3');

                $w = count($data[5+1]);

                $sheet->getStyle('A5:'.chr(ord('A') + $w - 1).'5')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
                    'C' => '0.00',

                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("B$m:".chr(ord('A') + $w - 1)."$n")->applyFromArray([
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
