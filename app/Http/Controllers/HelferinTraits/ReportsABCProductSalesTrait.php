<?php 

namespace App\Http\Controllers\HelferinTraits;

use Illuminate\Http\Request;

use App\Configuration;

use App\Product;
use App\Customer;

use App\CustomerShippingSlipLine;

use App\Tools;

use Carbon\Carbon;

use Excel;

trait ReportsABCProductSalesTrait
{

    /**
     * ABC Product Sales Report (ABC Analysis).
     *
     * @return Spread Sheet download
     */
    public function reportABCProductSales(Request $request)
    {
        // abi_r(Carbon::now()->month);die();

        $product_sales_month_from = $request->input('abc_product_sales_month_from', 1);
        
        $product_sales_month_to   = $request->input('abc_product_sales_month_to', Carbon::now()->month );

        $nbr_years = $request->input('abc_product_sales_years_to_compare', 1);

        $document_total_tax = $request->input('abc_product_sales_value', 'total_tax_incl');

        if ( !in_array($document_total_tax, ['total_tax_incl', 'total_tax_excl']) )
            $document_total_tax = 'total_tax_incl';
 
        $customer_id = $request->input('abc_product_sales_customer_id', 0);

        $model = $request->input('abc_product_sales_model', Configuration::get('RECENT_SALES_CLASS'));

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
//                            ->orderBy('reference', 'asc')
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

        // ABC Sorting
        $theYear = $list_of_years[0];
        $products = $products->SortByDesc($theYear);

        $abc_total = $products->sum($theYear);


        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $customer_label = 'todos';

        // Sheet Header Report Data
        $data[] = [\App\Context::getContext()->company->name_fiscal];

        $row = [];
        $row[] = 'Análisis ABC de Productos ('.l($model).') ';
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
        $header_names[] = 'Contribución (%)';
        $header_names[] = 'Acumulado (%)';

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

                $row[] = ($product->{$theYear} / $abc_total) * 100.0;
                $row[] = ($totals[$theYear] / $abc_total) * 100.0;
    
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
        Excel::create('ABC_Ventas_Productos', function($excel) use ($sheetName, $data, $nbr_years) {

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
                    'D' => '0.00',
                    'E' => '0.00',

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
    }

}