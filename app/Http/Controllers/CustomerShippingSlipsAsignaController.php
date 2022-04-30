<?php

namespace App\Http\Controllers;

use App\Helpers\AsignaExcel;
use App\Helpers\Exports\ArrayExport;
use App\Models\Configuration;
use App\Models\CustomerShippingSlip;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CustomerShippingSlipsAsignaController extends Controller
{
   protected $customershippingslip;

   public function __construct(CustomerShippingSlip $customershippingslip)
   {
        $this->customershippingslip = $customershippingslip;
   }

    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export($id, Request $request)
    {
        $asigna_id = AsignaExcel::getAsignaId();

        $documents = $this->customershippingslip
                        ->where('carrier_id', $asigna_id)
                        ->where('id', $id)
                        ->get();    // findOrFail($id);
        

        // So far, so good!
        return $this->exportProcess( $documents );
   }

    /**
     * Export a file of the resource (bulk).
     *
     * @return 
     */
    public function exportBulk(Request $request)
    {
        //
        // Get Document IDs & constraints
        $document_list = $request->input('document_group', []);

        if ( count( $document_list ) == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records selected. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));

        $asigna_id = AsignaExcel::getAsignaId();
        
        $documents = $this->customershippingslip
                        ->where('carrier_id', $asigna_id)
                        ->find( $document_list );

        if ( $documents->count() == 0 ) 
            return redirect()->back()
                ->with('warning', l('No records found. ', 'layouts').l('No action is taken &#58&#58 (:id) ', ['id' => ''], 'layouts'));

        
        // So far, so good!
        return $this->exportProcess( $documents );
   }

    /**
     * Export a file of the resource (Process).
     *
     * @return 
     */
    public function exportProcess( $documents )
    {
        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $data[] = AsignaExcel::headers();

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($documents as $document) {

            $row = AsignaExcel::rowTemplate();

//            $row['ShippingDate'] = abi_date_form_short( $sheet->due_date );

            $row['Albaran'] = $document->document_reference;   // Document reference

            $row['Bultos']  = $document->number_of_packages != 0 ? $document->number_of_packages : 1;
            $row['Kilos']   = AsignaExcel::formatNumber($document->weight);
            $row['Volumen'] = AsignaExcel::formatNumber($document->volume);

            $recipient_name = (string) $document->shippingaddress->name_commercial;

            if ( strlen($recipient_name) == 0 )
                $recipient_name = (string) $document->shippingaddress->contact_name;

            if ( strlen($recipient_name) == 0 )
                $recipient_name = (string) $document->customer->name_regular;

            $row['Destinatario'] = $recipient_name;     // Nombre destinatario

            // Free new field ;)             // Dirección de Envío, persona de contacto
            $row['Direccion'] = $document->shippingaddress->address1.' '.$document->shippingaddress->address2;

            $phone = $document->shippingaddress->phone;

            if ( strlen($phone) < 6 )
                $phone = $document->shippingaddress->phone_mobile;

            if ( strlen($phone) < 6 )
                $phone = $document->billingaddress->phone;

            if ( strlen($phone) < 6 )
                $phone = $document->billingaddress->phone_mobile;

            $row['Telefono'] = $phone;
            $row['Poblacion'] = $document->shippingaddress->city; // Población
            $row['Cod.Postal'] = $document->shippingaddress->postcode;

            $row['Observaciones'] = $document->notes_to_customer;

            $data[] = $row;

        }


        $styles = [];

        $columnFormats = [
//            'D' => NumberFormat::FORMAT_TEXT,
//            'C' => NumberFormat::FORMAT_NUMBER_00,
//            'D' => NumberFormat::FORMAT_NUMBER,
        ];

        $csvSettings = [
            'delimiter' => ';',
//            'use_bom' => false,
//            'output_encoding' => 'ISO-8859-1',
        ];

        $sheetTitle = 'Asigna';

        $export = (new ArrayExport($data, $styles, $sheetTitle, $columnFormats))->setCsvSettings($csvSettings);

        $sheetFileName = 'Asigna '.abi_date_full( Carbon::now(), 'Y-m-d H_i_s' );

        // Generate and return the spreadsheet
        return Excel::download($export, $sheetFileName.'.csv');

    }
}
