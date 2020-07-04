<?php

namespace App\Http\Controllers;

use App\ProductionSheet;
use Illuminate\Http\Request;

use WooCommerce;
use Automattic\WooCommerce\HttpClient\HttpClientException as WooHttpClientException;

class ProductionSheetsPdfController extends Controller
{


   protected $productionSheet;

   protected $families = [
                'espelta' => [
                    'key'        => 'espelta',
                    'label'      => 'Espelta',
                    'references' => ['1000', '1010', '1001', '1011', '1002', '1012'],
                    'assemblies' => ['10601', '10610', '10611'],

                    'work_center_id' => 1,
                ],
                'trigo' => [
                    'key'        => 'trigo',
                    'label'      => 'Trigo',
                    'references' => ['1003', '1013', '1004', '1014'],
                    'assemblies' => ['10709', '10710'],

                    'work_center_id' => 1,
                ],
                'centeno' => [
                    'key'        => 'centeno',
                    'label'      => 'Centeno',
                    'references' => ['1006', '1016'],
                    'assemblies' => ['10801'],

                    'work_center_id' => 1,
                ],
                'combi' => [
                    'key'        => 'combi',
                    'label'      => 'Combi',
                    'references' => ['1005', '1015'],
                    'assemblies' => ['10802'],

                    'work_center_id' => 1,
                ],
                'multigrano' => [
                    'key'        => 'multigrano',
                    'label'      => 'Multigrano',
                    'references' => ['1007'],
                    'assemblies' => ['10803'],

                    'work_center_id' => 1,
                ],
                'chapatayhogaza' => [
                    'key'        => 'chapatayhogaza',
                    'label'      => 'Chapatas y Hogazas',
                    'references' => [ '1104', '1105', '1106', '1107', '1108', '1109', '1100', '1101', '1102'],
                    'assemblies' => ['10700', '10703', '10704', '10705', '10706', '10707', '10708'],

                    'work_center_id' => 1,
                ],
                'candeal' => [
                    'key'        => 'candeal',
                    'label'      => 'Candeal',
                    'references' => ['1500', '1501'],
                    'assemblies' => ['10850'],

                    'work_center_id' => 1,
                ],
                'picoyreganats' => [
                    'key'        => 'picoyreganats',
                    'label'      => 'Picos y Regañás TS',
                    'references' => ['2001', '2002', '2100', '2031', '2032', '2051', '2052'],
                    'assemblies' => ['10900'],

                    'work_center_id' => 1,
                ],
                'picoyreganaesp' => [
                    'key'        => 'picoyreganaesp',
                    'label'      => 'Regañás Esp',
                    'references' => ['2011', '2012', '2041', '2042' ],
                    'assemblies' => ['10910'],

                    'work_center_id' => 1,
                ],

                'mollete' => [
                    'key'        => 'mollete',
                    'label'      => 'Mollete',
                    'references' => ['4003', '5001'],
                    'assemblies' => ['41100', '40100'],

                    'work_center_id' => 2,
                ],
                'arroz' => [
                    'key'        => 'arroz',
                    'label'      => 'Arroz',
                    'references' => ['4001'],
                    'assemblies' => ['41101', '40101'],

                    'work_center_id' => 2,
                ],
                'maiz' => [
                    'key'        => 'maiz',
                    'label'      => 'Maíz',
                    'references' => ['4002', '4011', '4012', '4006'],
                    'assemblies' => ['40102', '41102'],

                    'work_center_id' => 2,
                ],

                'sarracenoh' => [
                    'key'        => 'sarracenoh',
                    'label'      => 'Sarraceno H',
                    'references' => ['4023', '4022'],
                    'assemblies' => ['40104', '40105'],

                    'work_center_id' => 2,
                ],
                'sarracenosem' => [
                    'key'        => 'sarracenosem',
                    'label'      => 'Sarraceno Semillas',
                    'references' => ['4021'],
                    'assemblies' => ['40103'],

                    'work_center_id' => 2,
                ],
                'sarraceno100' => [
                    'key'        => 'sarraceno100',
                    'label'      => 'Sarraceno 100%',
                    'references' => ['4024'],
                    'assemblies' => ['40106'],

                    'work_center_id' => 2,
                ],


                'pansingluten' => [
                    'key'        => 'pansingluten',
                    'label'      => 'Panes sin Gluten',
                    'references' => ['4003', '5001', '4001', '4002', '4011', '4012', '4006', 
                                     '4023', '4022', '4021', '4024'],
                    'assemblies' => ['41100', '40100', '41101', '40101', '41102', '40102', 
                                     '40104', '40105', '40103', '40106'],

                    'work_center_id' => 2,
                ],


                'bizcocholimbrown' => [
                    'key'        => 'bizcocholimbrown',
                    'label'      => 'Bizcochos Limón y Brownies',
                    'references' => ['3020', '6020', '6000',
                                     '3021', '6021', '6001',
                                     '3022', '6022', '6002',
                                     '3023', '6023', '6003',
                                     '3040', '3041',
                                     '3024', '6024', '6004', '3050', '6009'
                                    ],
                    'assemblies' => ['30100', '30200', '30101', '30201', '30102', '30202', '30103', '30203',
                                     '30300',
                                     '30104', '30204',
                                    ],

                    'work_center_id' => 2,
                ],

                'bizcocho' => [
                    'key'        => 'bizcocho',
                    'label'      => 'Bizcochos',
                    'references' => ['3030', '6030', '6010', '3080',
                                     '3031', '6031', '6011',
                                     '3032', '6032', '6012',
                                     '3033', '6033', '6013', '6019',
                                     '3034', '6034', '6014'
                                    ],
                    'assemblies' => ['30105', '30205',
                                     '30106', '30206',
                                     '30107', '30207',
                                     '30108', '30208',
                                     '30109', '30209',
                                    ],

                    'work_center_id' => 2,
                ],
    ];

   public function __construct(ProductionSheet $productionSheet)
   {
        $this->productionSheet = $productionSheet;
   }



/* ********************************************************************************************* */    

    /**
     * PDF Stuff.
     *
     * 
     */

/* ********************************************************************************************* */    


    public function getPdfSummary(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);
        $work_center = \App\WorkCenter::find($request->input('work_center_id', 0));
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);
        // $sheet->customerorders()->load(['customer', 'customerorderlines']);



        //
        // return view('production_sheets.reports.production_sheets.summary', compact('sheet', 'work_center'));

        // PDF::setOptions(['dpi' => 150]);     // 'defaultFont' => 'sans-serif']);

        $pdf = \PDF::loadView('production_sheets.reports.production_sheets.summary', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

        return $pdf->stream('summary.pdf'); // $pdf->download('invoice.pdf');
    }


    public function getPdfPreassemblies(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);
        $work_center = \App\WorkCenter::find($request->input('work_center_id', 0));
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);
        // $sheet->customerorders()->load(['customer', 'customerorderlines']);



        //
        // return view('production_sheets.reports.preassemblies.preassemblies', compact('sheet', 'work_center'));

        // PDF::setOptions(['dpi' => 150]);     // 'defaultFont' => 'sans-serif']);

        $pdf = \PDF::loadView('production_sheets.reports.preassemblies.preassemblies', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

        return $pdf->stream('preassemblies.pdf'); // $pdf->download('invoice.pdf');
    }


    public function getPdfManufacturing(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);
        // $sheet->customerorders()->load(['customer', 'customerorderlines']);

        $key = $request->input('key');

        if ( !array_key_exists($key, $this->families))
            return redirect()->back()->with('error', 'You naughty, naughty! ['.$key.']');

        $family = $this->families[$key];

        $work_center = \App\WorkCenter::find($family['work_center_id']);
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        //
        // return view('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'));

        // PDF::setOptions(['dpi' => 150]);     // 'defaultFont' => 'sans-serif']);

/*
        if (!$request->has('new'))
        {

            if ($request->has('screen')) return view('production_sheets.reports.production_orders_MS1.manufacturing', compact('sheet', 'work_center', 'family'));

            $pdf = \PDF::loadView('production_sheets.reports.production_orders_MS1.manufacturing', compact('sheet', 'work_center', 'family'))->setPaper('a4', 'vertical');

            return $pdf->stream('manufacturing.pdf');

        }
*/

        if ($request->has('screen')) return view('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'));

        $pdf = \PDF::loadView('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'))->setPaper('a4', 'vertical');

        return $pdf->stream('manufacturing_'.$key.'.pdf'); // $pdf->download('invoice.pdf');
    }




    public function getBulkPdfManufacturing($id, $wc, Request $request)
    {
        $sheet = $this->productionSheet->findOrFail($id);

        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);
        // $sheet->customerorders()->load(['customer', 'customerorderlines']);

        // $work_center = \App\WorkCenter::find($family['work_center_id']);
        $work_center = \App\WorkCenter::findOrFail( $wc );
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);

        $key = $request->input('key');

        // if ( !array_key_exists($key, $this->families))
        //    return redirect()->back()->with('error', 'You naughty, naughty! ['.$key.']');

        $families = collect($this->families)->where('work_center_id', $wc);

        // abi_r($families->count());die();

        //
        // Do some house-keeping
        $storage_folder = 'ProductionSheets/';
        $currents =  \Storage::files($storage_folder);
        // Empty folder
        \Storage::delete( $currents );


        //
        // Loop through Documents
        $names = [];

        foreach ($families as $key => $family) {
            # code...
            // abi_r($key);

            $pdf = \PDF::loadView('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'))->setPaper('a4', 'vertical');

            $pdfName    = 'ProductionSheets_'.$key . '_' . $sheet->due_date.'.pdf';

            // if ($request->has('screen')) return view($template, compact('document', 'company'));

            $file_content = $pdf->output();
            \Storage::put($storage_folder.$pdfName, $file_content);

            $names[] = $pdfName;
        }


        //
        // It is time to merge Documents
        // include '../../Helpers/PDFMerger.php';

        $documents_path = storage_path().'/app/'.$storage_folder;
        $merged_pdf = new \PDFMerger;

        foreach ($names as $name) {
            # code...
            // $content = \Storage::get($storage_folder.$name);

            $merged_pdf->addPDF($documents_path.$name, 'all');

        }

        // Ta-chan!!
        $merged_pdf->merge('browser', 'samplepdfs/TEST2.pdf'); //REPLACE 'file' (first argument) WITH 'browser', 'download', 'string', or 'file' for output options. You do not need to give a file path for browser, string, or download - just the name.
    

        die();


        //
        // return view('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'));

        // PDF::setOptions(['dpi' => 150]);     // 'defaultFont' => 'sans-serif']);

/*
        if (!$request->has('new'))
        {

            if ($request->has('screen')) return view('production_sheets.reports.production_orders_MS1.manufacturing', compact('sheet', 'work_center', 'family'));

            $pdf = \PDF::loadView('production_sheets.reports.production_orders_MS1.manufacturing', compact('sheet', 'work_center', 'family'))->setPaper('a4', 'vertical');

            return $pdf->stream('manufacturing.pdf');

        }
*/

        if ($request->has('screen')) return view('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'));

        $pdf = \PDF::loadView('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'))->setPaper('a4', 'vertical');

        return $pdf->stream('manufacturing_'.$key.'.pdf'); // $pdf->download('invoice.pdf');
    }


/* ********************************************************************************************* */    


    public function getPdfOrders(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);
        $work_center = \App\WorkCenter::find($request->input('work_center_id', 0));
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);

        
        if ($request->has('extended'))
        {
            $pdf = \PDF::loadView('production_sheets.reports.summaries.orders_extended', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

            if ($request->has('screen')) return view('production_sheets.reports.summaries.orders_extended', compact('sheet', 'work_center'));

            return $pdf->stream('orders.pdf');
        }



        $pdf = \PDF::loadView('production_sheets.reports.summaries.orders', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

        if ($request->has('screen')) return view('production_sheets.reports.summaries.orders', compact('sheet', 'work_center'));

        return $pdf->stream('orders.pdf'); // $pdf->download('invoice.pdf');
    }


    public function getPdfShippingslips(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);
        $work_center = \App\WorkCenter::find($request->input('work_center_id', 0));
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        $sheet->load(['customershippingslips', 'customershippingslips.customer', 'customershippingslips.lines']);

 /*       
        if ($request->has('extended'))
        {
            $pdf = \PDF::loadView('production_sheets.reports.summaries.orders_extended', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

            if ($request->has('screen')) return view('production_sheets.reports.summaries.orders_extended', compact('sheet', 'work_center'));

            return $pdf->stream('orders.pdf');
        }
*/


        $pdf = \PDF::loadView('production_sheets.reports.summaries.shippingslips', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

        if ($request->has('screen')) return view('production_sheets.reports.summaries.shippingslips', compact('sheet', 'work_center'));

        return $pdf->stream('shippingslips.pdf'); // $pdf->download('invoice.pdf');
    }


    public function getPdfProducts(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);
        $work_center = \App\WorkCenter::find($request->input('work_center_id', 0));
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);

        
        if ($request->has('extended'))
        {
            $pdf = \PDF::loadView('production_sheets.reports.summaries.products_extended', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

            if ($request->has('screen')) return view('production_sheets.reports.summaries.products_extended', compact('sheet', 'work_center'));

            return $pdf->stream('products.pdf');
        }



        $pdf = \PDF::loadView('production_sheets.reports.summaries.products', compact('sheet', 'work_center'))->setPaper('a4', 'vertical');

        if ($request->has('screen')) return view('production_sheets.reports.summaries.products', compact('sheet', 'work_center'));

        return $pdf->stream('products.pdf'); // $pdf->download('invoice.pdf');
    }

}
