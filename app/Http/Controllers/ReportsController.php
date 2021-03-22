<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;

use App\Customer;
use App\CustomerOrder;
use App\CustomerShippingSlip;
use App\CustomerInvoice;

use App\Category;
use App\Product;
use App\Ecotax;

use App\Tools;

use Carbon\Carbon;

use Excel;

// Helferinnen
use App\Http\Controllers\HelferinTraits\ReportsABCProductSalesTrait;
use App\Http\Controllers\HelferinTraits\ReportsABCCustomerSalesTrait;

use App\Traits\DateFormFormatterTrait;

class ReportsController extends Controller
{
   
   use ReportsABCProductSalesTrait;
   use ReportsABCCustomerSalesTrait;

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


    /**
     * Product Sales Report.
     *
     * @return Spread Sheet download
     */
    public function reportProductSales(Request $request)
    {
        // abi_r(Carbon::now()->month);die();

        $product_sales_month_from = $request->input('product_sales_month_from', 1);
        
        $product_sales_month_to   = $request->input('product_sales_month_to', Carbon::now()->month );

        $nbr_years = $request->input('product_sales_years_to_compare', 1);

        $document_total_tax = $request->input('product_sales_value', 'total_tax_incl');

        if ( !in_array($document_total_tax, ['total_tax_incl', 'total_tax_excl']) )
            $document_total_tax = 'total_tax_incl';
 
        $customer_id = $request->input('product_sales_customer_id', 0);

        $model = $request->input('product_sales_model', Configuration::get('RECENT_SALES_CLASS'));

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
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);

        $selectorMonthList = Tools::selectorMonthList();

        $document_reference_date = 'close_date';        // 'document_date'
        $document_reference_date = 'document_date';        // 'document_date'

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
                        ->whereHas('document', function ($query) use ( $customer_id, $product_date_from, $product_date_to, $document_reference_date, $is_invoiceable_flag) {

                                // Closed Documents only
                                $query->where($document_reference_date, '!=', null);

                                if ( $customer_id > 0 )
                                    $query->where('customer_id', $customer_id);

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

        $customer_label = (int) $customer_id > 0
                        ? Customer::findOrFail($customer_id)->name_regular
                        : 'todos';

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

        $data[] = ['Cliente: '. $customer_label];

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
                $row[] = (string) $product->name;

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
        Excel::create('Ventas_Productos', function($excel) use ($sheetName, $data, $nbr_years) {

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
                $sheet->mergeCells('A4:B4');

                $w = count($data[5+1]);

                $sheet->getStyle('A6:'.chr(ord('A') + $w - 1).'6')->applyFromArray([
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


    /**
     * Customer Sales Report.
     *
     * @return Spread Sheet download
     */
    public function reportCustomerSales(Request $request)
    {
        // abi_r(Carbon::now()->month);die();

        $customer_sales_month_from = $request->input('customer_sales_month_from', 1);
        
        $customer_sales_month_to   = $request->input('customer_sales_month_to', Carbon::now()->month );

        $nbr_years = $request->input('customer_sales_years_to_compare', 1);

        $document_total_tax = $request->input('customer_sales_value', 'total_tax_incl');

        if ( !in_array($document_total_tax, ['total_tax_incl', 'total_tax_excl']) )
            $document_total_tax = 'total_tax_incl';
 
        $customer_id = $request->input('customer_sales_customer_id', 0);
 
        $model = $request->input('customer_sales_model', Configuration::get('RECENT_SALES_CLASS'));

        // calculate dates
        // http://zetcode.com/php/carbon/
        $years = [];
        // Current year
        $current_year = Carbon::now()->year;
        $first_year = Carbon::now()->year - $nbr_years;
        $month_from = $customer_sales_month_from;
        $month_to   = $customer_sales_month_to;
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
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);

        $selectorMonthList = Tools::selectorMonthList();

        $document_reference_date = 'close_date';        // 'document_date'
        $document_reference_date = 'document_date';        // 'document_date'

        $is_invoiceable_flag = ($model == 'CustomerShippingSlip') ? true : false;

        // Wanna dance, Honey Bunny?

        
        // All customers. Lets see:
        $customers = Customer::select('id', 'reference_external', 'name_fiscal', 'name_commercial')
                            ->when($customer_id>0, function ($query) use ($customer_id) {

                                    $query->where('id', $customer_id);
                            })
                            ->orderBy('reference_external', 'asc')
                            ->orderBy('id', 'asc')
//                            ->take(4)
                            ->get();

        // abi_r($customers->count(), true);

$k=0;
// Nice! Lets move on and retrieve Documents
foreach ($customers as $customer) {
        # code...
        // Initialize
//        $customer-> = 0.0;

    $customer_id = $customer->id;

    $customer_date_from = $date_from->copy();
    $customer_date_to   = $date_to->copy();

    foreach ($list_of_years as $year) {

        // abi_r($customer->name);
        // abi_r($customer_date_from);
        // abi_r($customer_date_to);

        $customer->{$year} = $class::
                          where('line_type', 'product')
                        ->whereHas('document', function ($query) use ( $customer_id, $customer_date_from, $customer_date_to, $document_reference_date, $is_invoiceable_flag ) {

                                if ( $customer_id > 0 )
                                    $query->where('customer_id', $customer_id);

                                // Closed Documents only
                                // $query->where($document_reference_date, '!=', null);

                                // Only invoiceable Documents when Documents are Customer Shipping Slips
                                if ( $is_invoiceable_flag )
                                    $query->where('is_invoiceable', '>', 0);

                                if ( $customer_date_from )
                                    $query->where($document_reference_date, '>=', $customer_date_from);
                                
                                if ( $customer_date_to )
                                    $query->where($document_reference_date, '<=', $customer_date_to);
                        })
                        ->sum($document_total_tax);

        $customer_date_from->addYear();
        $customer_date_to->addYear();

    }
    $k++;
    // if ($k==4) break;
}
// die();
// abi_r($customers, true);
        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $customer_label = (int) $customer_id > 0
                        ? Customer::findOrFail($customer_id)->name_regular
                        : 'todos';

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];

        $row = [];
        $row[] = 'Listado de Ventas comparativas por Cliente ('.l($model).') ';
        foreach ($list_of_years as $year) {
            // $row[] = '';
        }
        $row[] = '';
        $row[] = '';
        $row[] = date('d M Y H:i:s');
        $data[] = $row;

        $data[] = ['Cliente: '. $customer_label];

        $ribbon = $document_total_tax == 'total_tax_incl' ?
                                            'Ventas son con Impuestos incluidos.' :
                                            'Ventas son sin Impuestos.';
        // $data[] = [ 'Listado de Ventas comparativas por customero ('.l($model).') ', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Meses: desde '.$selectorMonthList[$customer_sales_month_from].' hasta '.$selectorMonthList[$customer_sales_month_to].'. '.$ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['ID', 'Referencia', 'Nombre', ];
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

        foreach ($customers as $customer) 
        {
                $row = [];
                $row[] = $customer->id;
                $row[] = (string) $customer->reference_external;
                $row[] = (string) $customer->name_regular;

                foreach ($list_of_years as $year) {
                    $row[] = (float) $customer->{$year};

                    $totals[$year] += (float) $customer->{$year};
                }
    
                $data[] = $row;

        }

        // Totals
        $data[] = [''];

        $row = [];
        $row[] = '';
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
        Excel::create('Ventas_Clientes', function($excel) use ($sheetName, $data, $nbr_years) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data, $nbr_years) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('D2:'.chr(ord('D') + $nbr_years).'2');   // https://stackoverflow.com/questions/39314048/increment-letters-like-number-by-certain-value-in-php
                $sheet->mergeCells('A3:C3');
                $sheet->mergeCells('A4:C4');

                $w = count($data[5+1]);

                $sheet->getStyle('A6:'.chr(ord('A') + $w - 1).'6')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
                    'D' => '0.00',

                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("C$m:".chr(ord('A') + $w - 1)."$n")->applyFromArray([
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


    /**
     * Customer Sales Report.
     *
     * @return Spread Sheet download
     */
    public function reportCustomerServices(Request $request)
    {
        // abi_r(Carbon::now()->month);die();

        $customer_sales_month_from = $request->input('customer_sales_month_from', 1);
        
        $customer_sales_month_to   = $request->input('customer_sales_month_to', Carbon::now()->month );

        $nbr_years = $request->input('customer_sales_years_to_compare', 1);

        $document_total_tax = $request->input('customer_sales_value', 'total_tax_incl');

        if ( !in_array($document_total_tax, ['total_tax_incl', 'total_tax_excl']) )
            $document_total_tax = 'total_tax_incl';
 
        $customer_id = $request->input('customer_sales_customer_id', 0);
 
        $model = $request->input('customer_sales_model', Configuration::get('RECENT_SALES_CLASS'));

        // calculate dates
        // http://zetcode.com/php/carbon/
        $years = [];
        // Current year
        $current_year = Carbon::now()->year;
        $first_year = Carbon::now()->year - $nbr_years;
        $month_from = $customer_sales_month_from;
        $month_to   = $customer_sales_month_to;
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
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);

        $selectorMonthList = Tools::selectorMonthList();

        $document_reference_date = 'close_date';        // 'document_date'
        $document_reference_date = 'document_date';        // 'document_date'

        $is_invoiceable_flag = ($model == 'CustomerShippingSlip') ? true : false;

        // Wanna dance, Honey Bunny?

        
        // All customers. Lets see:
        $customers = Customer::select('id', 'reference_external', 'name_fiscal', 'name_commercial')
                            ->when($customer_id>0, function ($query) use ($customer_id) {

                                    $query->where('id', $customer_id);
                            })
                            ->orderBy('reference_external', 'asc')
                            ->orderBy('id', 'asc')
//                            ->take(4)
                            ->get();

        // abi_r($customers->count(), true);

$k=0;
// Nice! Lets move on and retrieve Documents
foreach ($customers as $customer) {
        # code...
        // Initialize
//        $customer-> = 0.0;

    $customer_id = $customer->id;

    $customer_date_from = $date_from->copy();
    $customer_date_to   = $date_to->copy();

    foreach ($list_of_years as $year) {

        // abi_r($customer->name);
        // abi_r($customer_date_from);
        // abi_r($customer_date_to);

        $customer->{$year} = $class::
                          where( function ($q) {
                                $q->where(  'line_type', 'service' );
                                $q->orWhere('line_type', 'shipping');
                          })
                        ->whereHas('document', function ($query) use ( $customer_id, $customer_date_from, $customer_date_to, $document_reference_date, $is_invoiceable_flag ) {

                                if ( $customer_id > 0 )
                                    $query->where('customer_id', $customer_id);

                                // Closed Documents only
                                // $query->where($document_reference_date, '!=', null);

                                // Only invoiceable Documents when Documents are Customer Shipping Slips
                                if ( $is_invoiceable_flag )
                                    $query->where('is_invoiceable', '>', 0);

                                if ( $customer_date_from )
                                    $query->where($document_reference_date, '>=', $customer_date_from);
                                
                                if ( $customer_date_to )
                                    $query->where($document_reference_date, '<=', $customer_date_to);
                        })
                        ->sum($document_total_tax);

        $customer_date_from->addYear();
        $customer_date_to->addYear();

    }
    $k++;
    // if ($k==4) break;
}
// die();
// abi_r($customers, true);
        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $customer_label = (int) $customer_id > 0
                        ? Customer::findOrFail($customer_id)->name_regular
                        : 'todos';

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];

        $row = [];
        $row[] = 'Listado de Ventas de Servicios comparativas por Cliente ('.l($model).') ';
        foreach ($list_of_years as $year) {
            // $row[] = '';
        }
        $row[] = '';
        $row[] = '';
        $row[] = date('d M Y H:i:s');
        $data[] = $row;

        $data[] = ['Cliente: '. $customer_label];

        $ribbon = $document_total_tax == 'total_tax_incl' ?
                                            'Ventas son con Impuestos incluidos.' :
                                            'Ventas son sin Impuestos.';
        // $data[] = [ 'Listado de Ventas comparativas por customero ('.l($model).') ', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Meses: desde '.$selectorMonthList[$customer_sales_month_from].' hasta '.$selectorMonthList[$customer_sales_month_to].'. '.$ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['ID', 'Referencia', 'Nombre', ];
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

        foreach ($customers as $customer) 
        {
                $row = [];
                $row[] = $customer->id;
                $row[] = (string) $customer->reference_external;
                $row[] = (string) $customer->name_regular;

                foreach ($list_of_years as $year) {
                    $row[] = (float) $customer->{$year};

                    $totals[$year] += (float) $customer->{$year};
                }
    
                $data[] = $row;

        }

        // Totals
        $data[] = [''];

        $row = [];
        $row[] = '';
        $row[] = '';
        $row[] = 'Total:';
        foreach ($list_of_years as $year) {
            $row[] = (float) $totals[$year];
        }
        $data[] = $row;

        // abi_r($data, true);

//        $i = count($data);

        $sheetName = 'Ventas Servicios ' . l($model);

        // Generate and return the spreadsheet
        Excel::create('Ventas_Servicios_Clientes', function($excel) use ($sheetName, $data, $nbr_years) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data, $nbr_years) {
                
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('D2:'.chr(ord('D') + $nbr_years).'2');   // https://stackoverflow.com/questions/39314048/increment-letters-like-number-by-certain-value-in-php
                $sheet->mergeCells('A3:C3');
                $sheet->mergeCells('A4:C4');

                $w = count($data[5+1]);

                $sheet->getStyle('A6:'.chr(ord('A') + $w - 1).'6')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
                    'D' => '0.00',

                ));
                
                $n = count($data);
                $m = $n;    //  - 3;
                $sheet->getStyle("C$m:".chr(ord('A') + $w - 1)."$n")->applyFromArray([
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


    /**
     * Category Sales Report.
     *
     * @return Spread Sheet download
     */
    public function reportCategorySales(Request $request)
    {
        // abi_r(Carbon::now()->month);die();

        $category_sales_month_from = $request->input('category_sales_month_from', 1);
        
        $category_sales_month_to   = $request->input('category_sales_month_to', Carbon::now()->month );

        $nbr_years = $request->input('category_sales_years_to_compare', 1);

        $document_total_tax = $request->input('category_sales_value', 'total_tax_incl');

        if ( !in_array($document_total_tax, ['total_tax_incl', 'total_tax_excl']) )
            $document_total_tax = 'total_tax_incl';
 
        $customer_id = $request->input('category_sales_customer_id', 0);
 
        $model = $request->input('category_sales_model', Configuration::get('RECENT_SALES_CLASS'));

        // calculate dates
        // http://zetcode.com/php/carbon/
        $years = [];
        // Current year
        $current_year = Carbon::now()->year;
        $first_year = Carbon::now()->year - $nbr_years;
        $month_from = $category_sales_month_from;
        $month_to   = $category_sales_month_to;
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
        $table = \Str::snake(\Str::plural($model));
        $route = str_replace('_', '', $table);

        $selectorMonthList = Tools::selectorMonthList();

        $document_reference_date = 'close_date';        // 'document_date'
        $document_reference_date = 'document_date';        // 'document_date'

        $is_invoiceable_flag = ($model == 'CustomerShippingSlip') ? true : false;

        // Wanna dance, Honey Bunny?

        
        // All categories. Lets see:
        $categories = Category::select('id', 'name')  // , 'measure_unit_id')
                            ->where('parent_id', '>', 0)    // Only "children"
//                            ->with('measureunit')
                            ->orderBy('name', 'asc')
//                            ->take(4)
                            ->get();

        // abi_r($categories->count(), true);

$k=0;
// Nice! Lets move on and retrieve Documents
foreach ($categories as $category) {
        # code...
        // Initialize
//        $category-> = 0.0;

    $category_id = $category->id;

    $category_date_from = $date_from->copy();
    $category_date_to   = $date_to->copy();

    foreach ($list_of_years as $year) {

        // abi_r($category->name);
        // abi_r($category_date_from);
        // abi_r($category_date_to);

        $category->{$year} = $class::
                          where('line_type', 'product')
                        ->whereHas('product', function ($query) use ( $category_id ) {

                                $query->where('category_id', $category_id);
                        })
                        ->whereHas('document', function ($query) use ( $customer_id, $category_date_from, $category_date_to, $document_reference_date, $is_invoiceable_flag) {

                                // Closed Documents only
                                $query->where($document_reference_date, '!=', null);

                                if ( $customer_id > 0 )
                                    $query->where('customer_id', $customer_id);

                                // Only invoiceable Documents when Documents are Customer Shipping Slips
                                if ( $is_invoiceable_flag )
                                    $query->where('is_invoiceable', '>', 0);

                                if ( $category_date_from )
                                    $query->where($document_reference_date, '>=', $category_date_from);
                                
                                if ( $category_date_to )
                                    $query->where($document_reference_date, '<=', $category_date_to);
                        })
                        ->sum($document_total_tax);

        $category_date_from->addYear();
        $category_date_to->addYear();

    }
    $k++;
    // if ($k==4) break;
}
// die();
// abi_r($categories, true);
        // Lets get dirty!!
        // See: https://laraveldaily.com/laravel-excel-export-formatting-and-styling-cells/


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $customer_label = (int) $customer_id > 0
                        ? Customer::findOrFail($customer_id)->name_regular
                        : 'todos';

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];

        $row = [];
        $row[] = 'Listado de Ventas comparativas por Categoría ('.l($model).') ';
        foreach ($list_of_years as $year) {
            // $row[] = '';
        }
        $row[] = '';
        $row[] = date('d M Y H:i:s');
        $data[] = $row;

        $data[] = ['Cliente: '. $customer_label];

        $ribbon = $document_total_tax == 'total_tax_incl' ?
                                            'Ventas son con Impuestos incluidos.' :
                                            'Ventas son sin Impuestos.';
        // $data[] = [ 'Listado de Ventas comparativas por categoryo ('.l($model).') ', '', '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = ['Meses: desde '.$selectorMonthList[$category_sales_month_from].' hasta '.$selectorMonthList[$category_sales_month_to].'. '.$ribbon];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['ID', 'Nombre', ];
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

        foreach ($categories as $category) 
        {
                $row = [];
                $row[] = $category->id;
                $row[] = (string) $category->name;

                foreach ($list_of_years as $year) {
                    $row[] = (float) $category->{$year};

                    $totals[$year] += (float) $category->{$year};
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
        Excel::create('Ventas_Categorias', function($excel) use ($sheetName, $data, $nbr_years) {

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
                $sheet->mergeCells('A4:B4');

                $w = count($data[5+1]);

                $sheet->getStyle('A6:'.chr(ord('A') + $w - 1).'6')->applyFromArray([
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
