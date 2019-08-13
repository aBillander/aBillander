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
