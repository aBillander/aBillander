<?php 

namespace App\Http\Controllers\HelferinTraits;

use Illuminate\Http\Request;

use App\Product;
use App\Customer;

use App\CustomerShippingSlipLine;

use Excel;

trait HelferinProductReorderTrait
{

    public function mfgIndex()
    {
        $product_mrptypeList = ['' => l('-- All --', 'layouts')] + Product::getMrpTypeList();

        $default_model = \App\Configuration::get('RECENT_SALES_CLASS');

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
        $data[] = [\App\Context::getContext()->company->name_fiscal];
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

        $sheetName = 'Re-Aprovisionamiento';

        // Generate and return the spreadsheet
        Excel::create('Re-Aprovisionamiento de Productos', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                
                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');

                $sheet->getStyle('A4:I4')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $sheet->setColumnFormat(array(
//                    'B' => 'dd/mm/yyyy',
//                    'C' => 'dd/mm/yyyy',
                    'A' => '@',
//                    'C' => '0.00',
                    'C' => '0',
                    'D' => '0.00',
                    'E' => '0.00',
                    'F' => '0.00',
                    'G' => '0.00',

                ));

                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');


        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));

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

        $sheetName = 'Re-Aprovisionamiento' ;

        // abi_r($data, true);

        // Generate and return the spreadsheet
        Excel::create('Re-Aprovisionamiento de Productos', function($excel) use ($sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');           // ->export('pdf');  <= Does not work. See: https://laracasts.com/discuss/channels/general-discussion/dompdf-07-on-maatwebsiteexcel-autoloading-issue

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs
    }

}