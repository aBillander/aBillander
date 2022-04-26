<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Customer;
use App\Models\CustomerShippingSlipLine;
use App\Models\Product;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait HelferinProductReorderTrait
{

    public function mfgIndex()
    {
        $product_mrptypeList = ['' => l('-- All --', 'layouts')] + Product::getMrpTypeList();

        $default_model = Configuration::get('RECENT_SALES_CLASS');

        return view('helferin.home_mfg', compact('product_mrptypeList', 'default_model'));
    }


    public function reportProductReorder(Request $request)
    {
        $mrp_type = $request->input('mrp_type', null);
        
        // Products 
        $products = Product::select('id', 'name', 'reference', 'measure_unit_id', 'stock_control', 'mrp_type', 'reorder_point', 'maximum_stock', 'quantity_onhand')
                            ->when($mrp_type, function ($query, $mrp_type) {
                                return $query->where('mrp_type', $mrp_type);
                            })
                            ->with('measureunit')
                            ->orderBy('reference', 'asc')
                            ->get();

        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        $ribbon = 'Planificación: ' . ($mrp_type == '' ? 'todos' : $mrp_type);

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Re-Aprovisionamiento de Productos :: ' . $ribbon, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Referencia', 'Nombre', 
                        '¿Control de Stock?', 'Planificación', 'Stock reaprovisionamiento', 'Stock máximo', 
                        'Stock', 'Disponible', 'Unidad',

    ];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        foreach ($products as $product) 
        {
                $row = [];
                $row[] = (string) $product->reference;
                $row[] = $product->name;
                $row[] = $product->stock_control;
                $row[] = $product->mrp_type;
                $row[] = $product->reorder_point * 1.0;
                $row[] = $product->maximum_stock * 1.0;
                $row[] = $product->quantity_onhand *1.0;
                $row[] = $product->quantity_available *1.0;
                $row[] = $product->measureunit->sign;
    
                $data[] = $row;

        }


        $styles = [
            'A4:I4'    => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:B1', 'A2:B2'];

        $sheetTitle = 'Re-Aprovisionamiento de Productos';

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }


    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function reportProductReorderHeaders()
    {
        
        // Products 
        $products = Product::select('id', 'name', 'reference', 'measure_unit_id', 'stock_control', 'mrp_type', 'reorder_point', 'maximum_stock')
                            ->with('measureunit')
                            ->orderBy('reference', 'asc')
                            ->get();


        // Initialize the array which will be passed into the Excel generator.
        $data = [];  

        // Define the Excel spreadsheet headers
        $headers = [ 'id', 'reference', 'name', 'stock_control', 'mrp_type', 'reorder_point', 'maximum_stock', 'MEASURE_UNIT_SIGN'
        ];

        $data[] = $headers;

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($products as $product) 
        {
            $row = [];
            $row[] = $product->id;
            $row[] = (string) $product->reference;
            $row[] = $product->name;
            $row[] = $product->stock_control;
            $row[] = $product->mrp_type;
            $row[] = $product->reorder_point * 1.0;
            $row[] = $product->maximum_stock * 1.0;
            $row[] = $product->measureunit->sign;

            $data[] = $row;
        }



        $styles = [
            'A8:Q8'    => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
//            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_NUMBER_00,
        ];

        $merges = ['A1:C1', 'A2:C2', 'A3:C3', 'A4:C4', 'A5:C5', 'A6:C6'];

        $sheetTitle = 'Re-Aprovisionamiento';

        $export = new ArrayExport($data, [], $sheetTitle);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}