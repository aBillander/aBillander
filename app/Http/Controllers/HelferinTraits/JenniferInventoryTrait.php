<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Product;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait JenniferInventoryTrait
{

    public function reportInventory(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['inventory_date_to'], $request );

        $valuation_methodList = $this->valuation_methodList;

        $valuation_method = $request->input('valuation_method');

        if ( !array_key_exists($valuation_method, $valuation_methodList))
            $valuation_method = array_keys($valuation_methodList)[0];     // count($arr) ? array_keys($arr)[0] : null;


        $products = Product::
                            with('measureunit')
//                          ->with('combinations')                                  
                          ->with('category')
                          ->with('tax')
                          ->with('ecotax')
                          ->with('supplier')
                          ->orderBy('reference', 'asc')
                          ->get();


        $date = $request->input('inventory_date_to')
                ? Carbon::createFromFormat('Y-m-d', $request->input('inventory_date_to'))
                : Carbon::now();

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
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
//            $row[] = $product->reference." [".$product->id."]";
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
//            $row[] = $product->getStockToDateByWarehouse( 1, Carbon::createFromFormat('Y-m-d', $request->input('inventory_date_to')) );

            $data[] = $row;
            // $total_cost_average  += $row[2];
            // $total_cost  += $row[3];
            // $total_price += $row[4];
            $total_value += $row[8];

            // $arr = $product->getStockToDateFull( $date );
            // if ( $arr['stock'] != $arr['stock1'] )
            //     abi_r($product->reference.' - '.$arr['stock'].' - '.$arr['stock1']);
        }

        // Totals
        $data[] = [''];
        // $data[] = ['', 'Total:', $total_cost_average, $total_cost, $total_price, ''];
        $data[] = ['', '', '', '', '', '', '', 'Total:', $total_value, ''];


        $n = count($data);

        $styles = [
            'A5:K5'    => ['font' => ['bold' => true]],
            "B$n:I$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER,
        ];

        $merges = ['A1:B1', 'A2:B2', 'A3:B3'];

        $sheetTitle = 'Inventario '.$date->format('Y-m-d');

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}