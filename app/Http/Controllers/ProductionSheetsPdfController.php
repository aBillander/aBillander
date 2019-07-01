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
                    'references' => ['1_Z', '1000', '1010', '1001', '1011', '1002', '1012'],
                    'assemblies' => ['1_X', '1_Y', '10601', '10610', '10611']
                ],
                'trigo' => [
                    'key'        => 'trigo',
                    'label'      => 'Trigo',
                    'references' => ['1003', '1013', '1004', '1014'],
                    'assemblies' => ['10709', '10710']
                ],
                'centeno' => [
                    'key'        => 'centeno',
                    'label'      => 'Centeno',
                    'references' => ['1006', '1016'],
                    'assemblies' => ['10801']
                ],
                'combi' => [
                    'key'        => 'combi',
                    'label'      => 'Combi',
                    'references' => ['1005', '1015'],
                    'assemblies' => ['10802']
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

        $pdf = \PDF::loadView('production_sheets.reports.production_sheets.summary', compact('sheet', 'work_center'))->setPaper('a4', 'vetical');

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

        $pdf = \PDF::loadView('production_sheets.reports.preassemblies.preassemblies', compact('sheet', 'work_center'))->setPaper('a4', 'vetical');

        return $pdf->stream('preassemblies.pdf'); // $pdf->download('invoice.pdf');
    }


    public function getPdfManufacturing(Request $request, $id)
    {
        $sheet = $this->productionSheet->findOrFail($id);
        $work_center = \App\WorkCenter::find($request->input('work_center_id', 0));
        if ( !$work_center ) $work_center = new \App\WorkCenter(['id' => 0, 'name' => l('All', 'layouts')]);


        $sheet->load(['customerorders', 'customerorders.customer', 'customerorders.customerorderlines']);
        // $sheet->customerorders()->load(['customer', 'customerorderlines']);

        $key = $request->input('key');

        if ( !array_key_exists($key, $this->families))
            return redirect()->back()->with('error', 'You naughty, naughty!');

        $family = $this->families[$key];


        //
        // return view('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'));

        // PDF::setOptions(['dpi' => 150]);     // 'defaultFont' => 'sans-serif']);

/*
        if (!$request->has('new'))
        {

            if ($request->has('screen')) return view('production_sheets.reports.production_orders_MS1.manufacturing', compact('sheet', 'work_center', 'family'));

            $pdf = \PDF::loadView('production_sheets.reports.production_orders_MS1.manufacturing', compact('sheet', 'work_center', 'family'))->setPaper('a4', 'vetical');

            return $pdf->stream('manufacturing.pdf');

        }
*/

        if ($request->has('screen')) return view('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'));

        $pdf = \PDF::loadView('production_sheets.reports.production_orders.manufacturing', compact('sheet', 'work_center', 'family'))->setPaper('a4', 'vetical');

        return $pdf->stream('manufacturing_'.$key.'.pdf'); // $pdf->download('invoice.pdf');
    }
}
