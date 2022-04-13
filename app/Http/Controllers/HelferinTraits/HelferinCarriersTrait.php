<?php 

namespace App\Http\Controllers\HelferinTraits;

use App\Helpers\Exports\ArrayExport;
use App\Models\Carrier;
use App\Models\Context;
use App\Models\CustomerShippingSlip;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

trait HelferinCarriersTrait
{

    public function reportCarriers(Request $request)
    {
        // Dates (cuen)
        $this->mergeFormDates( ['carriers_date_from', 'carriers_date_to'], $request );

        $date_from = $request->input('carriers_date_from')
                     ? Carbon::createFromFormat('Y-m-d', $request->input('carriers_date_from'))->startOfDay()
                     : null;
        
        $date_to   = $request->input('carriers_date_to'  )
                     ? Carbon::createFromFormat('Y-m-d', $request->input('carriers_date_to'  ))->endOfDay()
                     : null;

        //             abi_r($date_from.' - '.$date_to);die();

        $carrier_id = $request->input('carriers_carrier_id', null);

        
        // Get Shipping Slips. Lets see:
        $documents = CustomerShippingSlip::
                              when($carrier_id>0, function ($query) use ($carrier_id) {

                                    $query->where('carrier_id', $carrier_id);
                            })
                            ->with('customer')
                            ->with('carrier')
                            ->when($date_from, function($query) use ($date_from) {

                                    $query->where('document_date', '>=', $date_from.' 00:00:00');
                            })
                            ->when($date_to, function($query) use ($date_to) {

                                    $query->where('document_date', '<=', $date_to.' 23:59:59');
                            })
//                            ->where( function($query){
//                                        $query->where(   'status', 'confirmed' );
//                                        $query->orWhere( 'status', 'closed'    );
//                                } )
                            ->orderBy('document_prefix', 'desc')
                            ->orderBy('document_reference', 'asc')
//                            ->orderBy('document_date', 'asc')
                            ->get();
        
        // https://github.com/Maatwebsite/Laravel-Excel/issues/2161



        // Lets get dirty!!

        // Initialize the array which will be passed into the Excel generator.
        $data = [];

        if ( $request->input('carriers_date_from_form') && $request->input('carriers_date_to_form') )
        {
            $ribbon = 'entre ' . $request->input('carriers_date_from_form') . ' y ' . $request->input('carriers_date_to_form');

        } else

        if ( !$request->input('carriers_date_from_form') && $request->input('carriers_date_to_form') )
        {
            $ribbon = 'hasta ' . $request->input('carriers_date_to_form');

        } else

        if ( $request->input('carriers_date_from_form') && !$request->input('carriers_date_to_form') )
        {
            $ribbon = 'desde ' . $request->input('carriers_date_from_form');

        } else

        if ( !$request->input('carriers_date_from_form') && !$request->input('carriers_date_to_form') )
        {
            $ribbon = 'todas';

        }

        $ribbon = ':: fecha(s) ' . $ribbon;

        $carrier_label = (int) $carrier_id > 0
        				? Carrier::findOrFail($carrier_id)->name
        				: 'Todos';

        // Sheet Header Report Data
        $data[] = [Context::getContext()->company->name_fiscal];
        $data[] = ['Albaranes de Clientes ' . $ribbon, '', '', '', '', '', '', '', date('d M Y H:i:s')];
        $data[] = [''];
        $data[] = ['Cliente:', $carrier_label];
        $data[] = [''];


        // Define the Excel spreadsheet headers
        $header_names = ['Albarán', 'Fecha', 'Estado', 'Transportista', 'Bultos', 'Peso', 'Volumen', 'Nº Expedición', 'Cliente'];

        $data[] = $header_names;

        // Convert each member of the returned collection into an array,
        // and append it to the data array.

        foreach ($documents as $document) 
        {
                $row = [];
                $row[] = $document->document_reference;
                $row[] = abi_date_short($document->document_date);
                $row[] = $document->shipment_status_name;
                $row[] = optional($document->carrier)->name;
                $row[] = (int) $document->number_of_packages;
                $row[] = (float) $document->weight;
                $row[] = (float) $document->volume;
                $row[] = $document->tracking_number;
                $row[] = $document->customer->name_regular;
    
                $data[] = $row;

        }


        $styles = [
            'A4:I4'    => ['font' => ['bold' => true]],
        ];

        $columnFormats = [
            'A' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => '0.000',
        ];

        $merges = ['A1:C1', 'A2:C2'];

        $sheetTitle = 'Transportistas_' . $request->input('carriers_date_from') . '_' . $request->input('carriers_date_to');

        $export = new ArrayExport($data, $styles, $sheetTitle, $columnFormats, $merges);

        $sheetFileName = $sheetTitle;

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.xlsx');

    }

}