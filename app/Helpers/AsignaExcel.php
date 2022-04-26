<?php 

namespace App\Helpers;

use App\Models\Configuration;

class AsignaExcel {
	
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

'Albaran',       // $document->document_reference
'Bultos',
'Kilos',
'Volumen',          // m3
'Reembolso',        // Si: cobrar en la entrega
'Destinatario',     // Nombre comercial
'Direccion',
'Cod.Postal',
'Poblacion',
'Telefono',
'Observaciones',    // Notas para el Cliente  :: $document->notes_to_customer

        ];
    }

    public static function rowTemplate( ) 
    {
        //
        $columns = self::headers( );

        $row = array_fill_keys($columns, '');

        // Prefill columns with fixed values
        $row['Reembolso'] = 'No';

        return $row;
    }

    public static function getCarrierId( ) 
    {
        // aBillander Carrier id
        return Configuration::getInt('ASIGNA_CARRIER_ID');
    }

    public static function getAsignaId( ) 
    {
        // Asigna carrier_id (in aBillander)
        return self::getCarrierId();
    }


    public static function getCarrierLogoUrl( ) 
    {
        // Carrier Logo Url
        return 'http://www.logisticacanaria.es/img/logotrans.png';
    }

    public static function getAsignaLogoUrl( ) 
    {
        // Asigna Logo Url
        return self::getCarrierLogoUrl();
    }

    public static function formatNumber( $value ) 
    {
        $val = rtrim(str_replace('.', ',', strval($value) ), '0');

        if ( substr($val, -1) == ',' )
            $val = $val . '0';

        return $val;
    }
}