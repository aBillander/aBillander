<?php 

namespace App\Helpers;

class TourlineExcel {
	
    public function __construct(  )
    {
        //
    }

    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function headers( )
    {
        // 

        return [

'ClientCode',
'SenderDept',
'ClientReference',
'ShippingDate',
'ShippingTypeCode',
'SenderName',
'SenderAddress',
'SenderAddress2',
'RecipientName',
// Free new field ;)
'RecipientContactName',     // Dirección de Envío, persona de contacto
'RecipientAddress',
'RecipientPhone',
'RecipientAddres2',
'DestinPostalCode',
'DocumentoCount',
'PackageCount',
'WidthDeclared',
'HeightDeclared',
'LengthDeclared',
'WeightDeclared',
'RefundValue',
'ClientDeclaredValue',
'ClientCollectionValue',
'ShippingComments',
'IsSaturdayDelivery',
'HasReturn',
'HasFinalManagement',
'-',
'OriginCountryName',
'OriginPostalCode',
'SenderPhone',
'DestinCountryName',
'IsSenderSmsNotified',
'SenderSmsNotifyPhone',
'IsSenderEmailNotified',
'SenderEmailNotifyAddress',
'IsRecipientSmsNotified',
'RecipientSmsNotifyPhone',
'IsRecipientEmailNotified',
'RecipientEmailNotifiyAddress',
'HasControl',
'IsClientPodScanned',
'HasIdentificationDestin',
// Extra Stuff
'-',
'DocumentId',
'aBillanderCustomerId',
'CodigoClienteFactuSOL',

        ];
    }

    public static function rowTemplate( ) 
    {
        //
        $columns = self::headers( );

        $row = array_fill_keys($columns, '');

        // Prefill columns with fixed values
        $row['ClientCode'] = '10152';
//        $row['ShippingDate'] = abi_date_form_short( 'now' );      
        $row['ShippingTypeCode'] = '13H';
        $row['SenderName'] = 'LA EXTRANATURAL S.L.';
        $row['SenderAddress'] = 'C/ RODRIGUEZ DE LA FUENTE 18';
        $row['SenderAddress2'] = 'BRENES';
        $row['OriginCountryName'] = 'ESPAÑA';
        $row['OriginPostalCode'] = '41310';
        $row['SenderPhone'] = '692813253';
        $row['DestinCountryName'] = 'ESPAÑA';
        $row['IsSenderEmailNotified'] = 'N';
        $row['SenderEmailNotifyAddress'] = env('TOURLINE_NOTIFY_MAIL', 'notify@mail.com');

        $row['IsSaturdayDelivery'] = 'N';
        $row['HasReturn'] = 'N';
        $row['HasFinalManagement'] = 'N';
        $row['IsSenderSmsNotified'] = 'N';
        $row['IsRecipientSmsNotified'] = 'N';
        $row['IsRecipientEmailNotified'] = 'S';
        $row['HasControl'] = 'N';
        $row['IsClientPodScanned'] = 'N';
        $row['HasIdentificationDestin'] = 'N';
//        $row[''] = '';

        return $row;
    }

    public static function getTourlineId( ) 
    {
        // Tourlie carrier_id
        return 3;
    }

    public static function getTourlineLogoUrl( ) 
    {
        // Tourlie Logo Url
        return 'https://www.cttexpress.com/application/themes/images/logo-ctt-express.png';
    }
}