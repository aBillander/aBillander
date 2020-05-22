<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configuration;
use App\ProductionSheet;

use App\TourlineExcel;

use Excel;

class ProductionSheetsTourlineController extends Controller
{
   protected $productionSheet;

   public function __construct(ProductionSheet $productionSheet)
   {
        $this->productionSheet = $productionSheet;
   }

    /**
     * Export a file of the resource.
     *
     * @return 
     */
    public function export($id, Request $request)
    {
        $documents_relation = 'customershippingslips';

        if ($request->has('customerorders')) $documents_relation = 'customerorders';

        $tourline_id = TourlineExcel::getTourlineId();

        $sheet = $this->productionSheet
                        ->with($documents_relation)
                        ->findOrFail($id);

        $documents = $sheet->{$documents_relation}->where('carrier_id', $tourline_id);

        // Load Tourline Excel fields

        // abi_r($documents);die();


        // Initialize the array which will be passed into the Excel generator.
        $data = []; 

        // Define the Excel spreadsheet headers
        $data[] = TourlineExcel::headers();

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($documents as $document) {

            $row = TourlineExcel::rowTemplate();

            $row['ShippingDate'] = abi_date_form_short( $sheet->due_date );

            $row['ClientReference'] = $document->document_reference;   // Document reference
            if ( $document->shipment_service_type_tag != '' )
                $row['ShippingTypeCode'] = $document->shipment_service_type_tag;

            $contact_name = (string) $document->shippingaddress->contact_name;

            if ( strlen($contact_name) == 0 )
                $contact_name = (string) $document->shippingaddress->name_commercial;

            if ( strlen($contact_name) == 0 )
                $contact_name = (string) $document->customer->name_regular;

            $row['RecipientName'] = $contact_name;     // Nombre destinatario
            $row['RecipientAddress'] = $document->shippingaddress->address1.' '.$document->shippingaddress->address2;

            $phone = $document->shippingaddress->phone;

            if ( strlen($phone) < 6 )
                $phone = $document->shippingaddress->phone_mobile;

            if ( strlen($phone) < 6 )
                $phone = $document->billingaddress->phone;

            if ( strlen($phone) < 6 )
                $phone = $document->billingaddress->phone_mobile;

            $row['RecipientPhone'] = $phone;
            $row['RecipientAddres2'] = $document->shippingaddress->city; // Población
            $row['DestinPostalCode'] = $document->shippingaddress->postcode;
            $row['PackageCount'] =  $document->number_of_packages != 0 ? $document->number_of_packages : 1;      // Número de paquetes
            $row['WeightDeclared'] = $document->weight != 0 ? $document->weight : 1;    // Peso
            $row['ShippingComments'] = (string) ($document->notes_from_customer.' '.$document->notes);  // Observaciones
            $row['RecipientEmailNotifiyAddress'] = $document->shippingaddress->email;
//          $row[''] = '';

            // Extra Stuff
            $row['DocumentId'] = $document->id;
            $row['aBillanderCustomerId'] = $document->customer->id;
            $row['CodigoClienteFactuSOL'] = $document->customer->reference_external;

            $data[] = $row;

        }

        $sheetName = 'Tourline' ;

        // Generate and return the spreadsheet
        Excel::create('Tourline_Hoja_Produccion_'.$sheet->due_date, function($excel) use ($id, $sheetName, $data) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('Price List file');

            // Build the spreadsheet, passing in the data array
            $excel->sheet($sheetName, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');

        // https://www.youtube.com/watch?v=LWLN4p7Cn4E
        // https://www.youtube.com/watch?v=s-ZeszfCoEs
    }
}
