<?php 

namespace Queridiam\FSxConnector;

use Illuminate\Support\Facades\DB;
use Config;

use App\Customer as Customer;
use App\Address as Address;
use App\CustomerOrder as Order;
use App\CustomerOrderLine as OrderLine;
use App\CustomerOrderLineTax as OrderLineTax;
use App\Product;
use App\Combination;
use App\Tax;

use App\Configuration;

// use \aBillander\WooConnect\WooOrder;

use App\Traits\LoggableTrait;

class FSxFactuSOLWebSql {

    use LoggableTrait;

    public static  $smethods = NULL;
    public static  $gates = NULL;
    public static  $taxes = NULL;

    protected $data = [];
//    protected $run_status = true;       // So far, so good. Can continue export
//    protected $error = null;

    
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    
    // See: https://github.com/laravel-enso/DataImport

    public function __construct ()
    {
        // Start Logger
        $this->logger = self::loggerSetup( 'Actualizar la Base de Datos de FactuSOLWeb' );

    }

    public static function readAndUpdate()
    {
        $processor = new static();

        $processor->logInfo('Se actualizará la Base de Datos de FactuSOLWeb.');

        // Get default charset & collation
        $charset_collate = self::get_charset_collate();
        // https://phplaravel.wordpress.com/2016/07/06/using-mysql-utf8mb4-character-set-and-collation-with-laravel/

        $fsolweb_file = Configuration::get('FSOL_CBDCFG').Configuration::get('FSOL_CBRCFG');

        if (file_exists($fsolweb_file)) { 

            DB::statement( "DROP TABLE IF EXISTS `F_LPC`" );

            $gestor=fopen($fsolweb_file, "rb");
            $contenido=fread($gestor, filesize($fsolweb_file));
            fclose($gestor);
            // eof guess
            $endol = ";\r\n";
            if (!strpos($contenido, $endol, 0)) $endol = ";\n";
            $this_format=detect_utf_encodings($contenido);      // abi_r($this_format);die();
            if ($this_format!=0 ) $this_transform = 'utf8_encode';
            else  $this_transform = __NAMESPACE__.'\\'.'utf8_encoded';     // 'utf8_encoded';

            $sql_batch = explode($endol, $contenido);
            $sql_count = $sql_ok = 0;
            foreach($sql_batch as $sql) {
                
                // Ignore some tables
                if (   preg_match('/INSERT HIGH_PRIORITY INTO '.TABLA_CLIENTES.'/', $sql)
                    || preg_match('/INSERT HIGH_PRIORITY INTO '.TABLA_DIRECCIONES.'/', $sql)
                    || preg_match('/INSERT HIGH_PRIORITY INTO '.TABLA_DESCUENTOS.'/', $sql)
                    || preg_match('/INSERT HIGH_PRIORITY INTO '.TABLA_PEDIDOS.'/', $sql)
                    || preg_match('/INSERT HIGH_PRIORITY INTO '.TABLA_LINEAS_PEDIDOS.'/', $sql)
                    || preg_match('/INSERT HIGH_PRIORITY INTO '.TABLA_FACTURAS.'/', $sql)
                    || preg_match('/INSERT HIGH_PRIORITY INTO '.TABLA_LINEAS_FACTURAS.'/', $sql)

                   ) continue;
                
                // Right charset!   http://ranskills.wordpress.com/2011/10/04/how-to-solve-mysql-character-sets-and-collations-mixing-problems/
                if ( strpos($sql, 'CREATE TABLE', 0) !== false ) 
                    $sql.= " $charset_collate";

                $sql = str_replace( "`PREC3PCL` Decimal(12,4) NOT NULL, `PREC3PCL` Decimal(12,4) NOT NULL", 
                                    "`PREC3PCL` Decimal(12,4) NOT NULL", $sql );
                if ($sql && $sql != '') {
                        $sql_count++;
                    
                                
                        try {

                            DB::statement($this_transform($sql));

                            $sql_ok++;
                        }

                        catch( \Exception $e ) {

                            $processor->logInfo( 'No se ha podido ejecutar la sentencia: '.$sql );

                        }
                }
            }

            // So far, so GOOD!
            Configuration::updateValue('FSOLWEB_SQL_LAST_DBUPDATE', \Carbon\Carbon::now()->format('Y-m-d H:i:s'));

            $processor->logInfo( '<b>' .Configuration::get('FSOL_CBRCFG').'</b> - ' . 'Se han procesado: '.$sql_ok.' instrucciones'.($sql_count!=$sql_ok?' de un total de '.$sql_count:'').'.');


        // Load configuration data from table TABLA_CONFIGURACION (application wide parameters)
        if ( $conf_data = self::get_F_CFG_data() ) {
            
            Configuration::updateValue('FSOL_TCACFG', $conf_data->TCACFG);
            Configuration::updateValue('FSOL_AUSCFG', $conf_data->AUSCFG);
            Configuration::updateValue('FSOL_SPCCFG', $conf_data->SPCCFG);
              
            Configuration::updateValue('FSOL_PIV1CFG', $conf_data->PIV1CFG);
            Configuration::updateValue('FSOL_PIV2CFG', $conf_data->PIV2CFG);
            Configuration::updateValue('FSOL_PIV3CFG', $conf_data->PIV3CFG);
            
            Configuration::updateValue('FSOL_PRE1CFG', $conf_data->PRE1CFG);
            Configuration::updateValue('FSOL_PRE2CFG', $conf_data->PRE2CFG);
            Configuration::updateValue('FSOL_PRE3CFG', $conf_data->PRE3CFG);
            
    //      if ( get_option('FSOL_CIACFG') != $conf_data['CIACFG'] ) 
    //          $fsx_connector->errors[] = 'La ruta <span style=\'font-weight: bold\'>Subcarpeta de Imágenes de Artículos</span> no coincide.<br /> - FactuSOL: <span style=\'font-weight: bold\'>'.$conf_data['CIACFG'].'</span><br /> - FSx-Connector: <span style=\'font-weight: bold\'>'.get_option('FSOL_CIACFG').'</span><br />No podrá agregar una Imagen a un nuevo Producto.<br /><br />';
    //      if ( get_option('FSOL_CPVCFG') != $conf_data['CPVCFG'] ) 
    //          $fsx_connector->errors[] = 'La ruta <span style=\'font-weight: bold\'>Subcarpeta de Pedidos de Clientes</span> no coincide.<br /> - FactuSOL: <span style=\'font-weight: bold\'>'.$conf_data['CPVCFG'].'</span><br /> - FSx-Connector: <span style=\'font-weight: bold\'>'.get_option('FSOL_CPVCFG').'</span><br /> No podrá descargar Clientes.<br /><br />';
    //      if ( get_option('FSOL_CCLCFG') != $conf_data['CCLCFG'] ) 
    //          $fsx_connector->errors[] = 'La ruta <span style=\'font-weight: bold\'>Subcarpeta de Clientes Creados On Line</span> no coincide.<br /> - FactuSOL: <span style=\'font-weight: bold\'>'.$conf_data['CCLCFG'].'</span><br /> - FSx-Connector: <span style=\'font-weight: bold\'>'.get_option('FSOL_CCLCFG').'</span><br /> No podrá descargar Pedidos.<br /><br />';
    //      if (!count($fsx_connector->errors))   $fsx_connector->informations[] = $fsx_connector->l('Las rutas de las carpetas (Configuraciones Técnicas) son correctas.');
 
 /*           
            // Next Steps!
            $errorCount = (count($fsx_connector->errors)?'Pero primero deberá solucionar los errores':'');
            if ( intval(get_option('FSOL_IMPUESTO_DIRECTO_TIPO_1')) != -1 || intval(get_option('FSOL_IMPUESTO_DIRECTO_TIPO_2')) != -1 || intval(get_option('FSOL_IMPUESTO_DIRECTO_TIPO_3')) != -1 ) $fsx_connector->informations[] = 'Ahora puede actualizar el Catálogo (FSx-Catálogo)'.'. '.$errorCount;
            else $fsx_connector->warnings[] = 'Ahora debe actualizar el Diccionario (FSx-Diccionario)'.'. '.$errorCount;
 */

            // Summary
            $sec_count = DB::table( TABLA_SECCIONES )->count();
            $fam_count = DB::table( TABLA_FAMILIAS  )->count();
            $art_count = DB::table( TABLA_ARTICULOS )->count();
            $processor->logInfo( 'En total <b>'.$sec_count.'</b> Secciones, <b>'.$fam_count.'</b> Familias, <b>'.$art_count.'</b> Artículos listos para cargarse en el Catálogo de aBillander.');
            
    } else {
            $processor->logError( 'No se ha encontrado la tabla \'<b>F_CFG</b>\' de configuración de FactuSOL.' );
    }  
    /* Checks end*/

        } else {
                $processor->logError( 'No se encontró el fichero: <b>'.$fsolweb_file.'</b>.<br />NO se actualizó la Base de Datos de FactuSOLWeb.' );
        }


 //       abi_r($fsx_connector_informations);
 //       abi_r($fsx_connector_errors, true);


        return true;
    }



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get all of the invoices for the WC Order.
     */
    public function invoices()
    {
        return ;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    
    public function getRawData( )
    {
        return ;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    
    // Custom function


/* ********************************************************************************************* */   


    public static function get_charset_collate() 
    {
        // Get default database connection
        $conn = Config::get('database.connections.'.Config::get('database.default'));

        $charset_collate = '';

        if ( ! empty( $conn['charset'] ) )
            $charset_collate = "DEFAULT CHARACTER SET ".$conn['charset'];
        
        if ( ! empty( $conn['collation'] ) )
            $charset_collate .= " COLLATE ".$conn['collation'];

        return $charset_collate;
    }

    public static function get_F_CFG_data() 
    {

        // Start Logic Probe, now!
        try {

            $settings = \DB::table( TABLA_CONFIGURACION )->where('CODCFG', '1')->first();
        }

        catch( \Exception $e ) {

            $settings = null;     // Object!!

        }

        return $settings;
    }


}




// Unicode BOM is U+FEFF, but after encoded, it will look like this.
define ('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
define ('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
define ('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));
define ('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
define ('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));

function detect_utf_encoding($filename) {

    $text = @file_get_contents($filename);
  
  $text_encoding = mb_detect_encoding($text,"UTF-8, ISO-8859-1, ISO-8859-15"); // die($text_encoding);
  if (strpos($text_encoding,   "8859") >0) return  1;  // Need translation
  elseif (strpos($text_encoding, "-8") >0) return  0;
  else                                     return -1;  // Unknown
    $first2 = substr($text, 0, 2);
    $first3 = substr($text, 0, 3);
    $first4 = substr($text, 0, 3);
   
    if ($first3 == UTF8_BOM) return 'UTF-8';
    elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
    elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
    elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
    elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
    else   {$encoding = mb_detect_encoding($text, "UTF-8,ISO-8859-1,WINDOWS-1252"); ;}
}

function detect_utf_encodings($text) {

  //  die(__NAMESPACE__); // Queridiam\FSxConnector

// abi_r(x_x('text'));die();
  
  $text_encoding = mb_detect_encoding($text,"UTF-8, ISO-8859-1, ISO-8859-15"); // die($text_encoding);
  if (strpos($text_encoding,   "8859") >0) return  1;  // Need translation
  elseif (strpos($text_encoding, "-8") >0) return  0;
  else                                     return -1;  // Unknown
    $first2 = substr($text, 0, 2);
    $first3 = substr($text, 0, 3);
    $first4 = substr($text, 0, 3);
   
    if ($first3 == UTF8_BOM) return 'UTF-8';
    elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
    elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
    elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
    elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
    else   {$encoding = mb_detect_encoding($text, "UTF-8,ISO-8859-1,WINDOWS-1252"); ;}
}

function utf8_encoded($text) {
      return $text;
}



// Define the database table names used in the project
  define('TABLA_AGENTES'        , 'F_AGE');
  define('TABLA_ALMACENES'      , 'F_ALM');
  define('TABLA_ARTICULOS'      , 'F_ART');
  define('TABLA_AUT'            , 'F_AUT');
  define('TABLA_CONFIGURACION'  , 'F_CFG');
  define('TABLA_CLIENTES'       , 'F_CLI');
  define('TABLA_DESCUENTOS'     , 'F_DES');
  define('TABLA_DIRECCIONES'    , 'F_DIR');
  define('TABLA_EMPRESAS'       , 'F_EMP');
  define('TABLA_FACTURAS'       , 'F_FAC');
  define('TABLA_FAMILIAS'       , 'F_FAM');
  define('TABLA_FORMAS_PAGO'    , 'F_FPA');
  define('TABLA_LINEAS_FACTURAS', 'F_LFA');
  define('TABLA_LINEAS_PEDIDOS' , 'F_LPC');
  define('TABLA_LINEAS_TARIFAS' , 'F_LTA');
  define('TABLA_PEDIDOS'        , 'F_PCL');
  define('TABLA_SECCIONES'      , 'F_SEC');
  define('TABLA_STOCK'          , 'F_STO');
  define('TABLA_TARIFAS'        , 'F_TAR');
  
  define('TABLA_DICCIONARIO'    , 'fsx_dic');
  define('TABLE_FSX_LOG'        , 'fsx_log');
  define('TABLE_FSX_ORDERS'     , 'fsx_orders');
