<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Ecotax;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait HelferinEcotaxesTrait
{

    public function reportEcotaxes(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['ecotaxes_date_from', 'ecotaxes_date_to'], $request );

        $date_from = $request->input('ecotaxes_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('ecotaxes_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('ecotaxes_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('ecotaxes_date_to'  ))->endOfDay()
                     : null;

        $model = $request->input('ecotaxes_model', Configuration::get('RECENT_SALES_CLASS'));

        $models = $this->models;
        if ( !in_array($model, $models) )
            $model = Configuration::get('RECENT_SALES_CLASS');
        $document_model = $model;
        $document_class = '\\App\\Models\\'.$document_model;
        $model .= 'Line';
        $class = '\\App\\Models\\'.$model;
        $table = \Str::snake(\Str::plural($model));
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


/*

https://www.google.com/search?channel=fs&client=ubuntu&q=laravel+wherehas+timeout

https://stackoverflow.com/questions/46785552/poor-wherehas-performance-in-laravel

Change whereHas according to:

        Replay::whereHas('players', function ($query) {
            $query->where('battletag_name', 'test');
        })->limit(100);


        Replay::whereIn('id', function ($query) {
            $query->select('replay_id')->from('players')->where('battletag_name', 'test');
        })->limit(100);

Try this package: https://github.com/biiiiiigmonster/hasin

Maybe indexes:

ALTER TABLE `customer_invoice_lines` ADD INDEX(`line_type`);

ALTER TABLE `customer_invoice_lines` ADD INDEX(`ecotax_id`); 

*/


        // abi_r($document_class, true);   \App\Models\CustomerInvoice
        $nbr_documents = $document_class::
                          whereIn('id', function ($query) use ($document_model, $model) {

                                $query->select( \Str::snake($document_model).'_id' )
                                      ->from( \Str::snake(\Str::plural($model)) )
                                      ->where('line_type', 'product')->where('ecotax_id', '>', 0);
                        })
                        ->when($date_from, function($query) use ($date_from) {

                                $query->where('document_date', '>=', $date_from);
                        })
                        ->when($date_to, function($query) use ($date_to) {

                                $query->where('document_date', '<=', $date_to);
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


        // abi_r($nbr_documents, true);

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
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

        $n = count($data);
        $m = $n;    //  - 3;
        
        $styles = [
            'A4:E4'    => ['font' => ['bold' => true]],
//            "C$n:C$n"  => ['font' => ['bold' => true, 'italic' => true]],
            "C$m:E$n"  => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER_00,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];

        $merges = ['A1:C1', 'A2:C2'];

        $sheetTitle = 'Informe RAEE ' . l(str_replace("Line","",$model));

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}