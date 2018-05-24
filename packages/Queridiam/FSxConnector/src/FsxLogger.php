<?php

namespace Queridiam\FSxConnector;

use Illuminate\Database\Eloquent\Model;

class FsxLogger extends Model
{
    protected $timer_start;
    protected $timer_stop;
    protected $timer_total;

    private $write_to; //  = 'database';
    public  $msg_queue = array();


    protected $table = 'fsx_loggers';

    protected $fillable = [ 'type', 'message', 'date_added', 'secs_added' ];

    
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct( $write_to = 'database' ) 
    {
      // 
      $this->write_to = $write_to;
    }

    public function timer_start() 
    {
      /*
      // Start the clock for the page parse time log
      define('PAGE_PARSE_START_TIME', microtime());

      if (defined("PAGE_PARSE_START_TIME")) {
        $this->timer_start = PAGE_PARSE_START_TIME;
      } else {
        $this->timer_start = microtime();
      }
      */

      $this->timer_start = microtime();
    }

    public function timer_stop($display = 'false') 
    {
      $this->timer_stop = microtime();

      $time_start = explode(' ', $this->timer_start);
      $time_end = explode(' ', $this->timer_stop);

      $this->timer_total = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

      $this->write($_SERVER['REQUEST_URI'], $this->timer_total . 's');

      if ($display == 'true') {
        return $this->timer_display();
      }
    }

    public function timer_display() 
    {
      return '<span class="smallText">Parse Time: ' . $this->timer_total . 's</span>';
    }

    public function write($message='', $type='') 
    {  
      // $type='INFO', 'WARNING', 'ERROR'
      
      if ( !$message && !$type ) $message="Unknown error";
      $temp = explode(" ", microtime());

      if ($this->write_to == 'database')
      {  
          $dataSet = [ 
                          'type' => $type, 
                          'message' => $message, 
                          'date_added' => date('Y-m-d H:i:s', $temp[1]), 
                          'secs_added' => substr($temp[0], 2, 6), 
                        ];

          \DB::table( $this->getTable() )->insert( $dataSet );

/*      
              Db::getInstance()->Execute( "INSERT INTO `" . _DB_PREFIX_ . "fsxlogentry` (
                            id_fsxlogentry, line_type, line_message, date_added, secs_added) VALUES
                            (NULL, '".$type."', '".pSQL($message, true)."', '".date(Configuration::get('FSX_DATETIME'), $temp[1])."', '".substr($temp[0], 2, 6)."')" );
*/

      } else {

          $this->msg_queue[] = array( $type, $message, date('Y-m-d H:i:s', $temp[1]), substr($temp[0], 2, 6) );

      }
    }

    public function start() 
    {
          $this->write(LOG_INFO_1000, "INFO");
    }

    public function reset() 
    {
          // Table name: ((new self)->getTable());     or   $item->getTable();

          FsxLogger::truncate();

          $this->write(LOG_INFO_1010, "INFO");
    }

    public function empty() 
    {
          FsxLogger::truncate();
    }

    public function stop() 
    {
          $this->write(LOG_INFO_1020, "INFO");
    }

  }



  
  define('LOG_INFO_FORMAT' , 'color: black');
  define('LOG_AVISO_FORMAT', 'color: green; font-weight: bold');
  define('LOG_ERROR_FORMAT', 'color: red; font-weight: bold');
  define('LOG_TIMER_FORMAT', 'color: blue; font-weight: bold');



  // FSX LOG parameters
  define('LOG_SHOWOFF_FORMAT', 'color: green; font-weight: bold');

  define('LOG_INFO_1000' , 'LOG iniciado');
  define('LOG_INFO_1010' , 'LOG reiniciado');
  define('LOG_INFO_1020' , 'LOG terminado');

  define('LOG_INFO_6000' , '<b>Secciónes</b>: NO se cargan.');
  define('LOG_INFO_6005' , 'Sección [%s] %s se ha cargado a la Tienda.');
  define('LOG_AVISO_6010' , 'Sección [%s] %s ya existe en la Tienda. No se ha cargado.');
  define('LOG_AVISO_6210' , 'Se ha borrado una entrada del Diccionario (Sección).');
  define('LOG_ERROR_6215' , 'No se encuentra la Categoría %s correspondiente a la Sección [%s] %s.');
  define('LOG_ERROR_6220' , 'No se encuentra la Sección %s correspondiente a la Categoría [%s] %s.');
  define('LOG_ERROR_6225' , 'No se encuentra la Categoría %s. No se encuentra la Sección %s.');

  define('LOG_INFO_6100' , '<b>Familias</b>: NO se cargan.');
  define('LOG_INFO_6101' , '<b>Familias</b>: Se cargan en la raíz del Catálogo.');
  define('LOG_INFO_6105' , 'Familia [%s] %s se ha cargado a la Tienda.');
  define('LOG_AVISO_6110' , 'Familia [%s] %s ya existe en la Tienda. No se ha cargado.');
  define('LOG_AVISO_6310' , 'Se ha borrado una entrada del Diccionario (Familia).');
  define('LOG_ERROR_6315' , 'No se encuentra la Categoría %s correspondiente a la Familia [%s] %s.');
  define('LOG_ERROR_6320' , 'No se encuentra la Familia %s correspondiente a la Categoría [%s] %s.');
  define('LOG_ERROR_6325' , 'No se encuentra la Categoría %s. No se encuentra la Familia %s.');

  define('LOG_INFO_6500'  , '<b>Artículos</b>: NO se cargan.');
  define('LOG_INFO_6505'  , 'Artículo [%s] %s se ha cargado a la Tienda.');
  define('LOG_ERROR_6505' , 'Artículo [%s] %s <span style="'.LOG_SHOWOFF_FORMAT.'">NO</span> se ha cargado a la Tienda. No se ha encontrado la correspondencia en el Impuesto');
  define('LOG_AVISO_6510' , 'Artículo [%s] %s ya existe en la Tienda. No se ha cargado.');
  define('LOG_INFO_6550'  , 'Se ha actualizado el Precio de todos los Productos en la Tienda.');
  define('LOG_INFO_6551'  , 'Se ha actualizado el Stock de todos los Productos en la Tienda.');
  define('LOG_AVISO_6555' , 'Se ha desactivado el Producto [<b>%s</b>] <b>%s</b> en la Tienda (no se encontró en FactuSol).');

  define('LOG_INFO_8000'  , 'El Pedido %s se ha descargado correctamente. ');
  define('LOG_ERROR_8001' , 'No se pueden descargar Clientes. La carpeta destino no tiene permisos de escritura (%s).');
  define('LOG_INFO_8004'  , 'El Cliente <span style="'.LOG_SHOWOFF_FORMAT.'">(%s) %s</span> del Pedido %s ya está en FactuSOL (%s). No se ha creado un fichero para descargarlo.');
  define('LOG_INFO_8005'  , 'Se ha creado un fichero de Cliente <span style="'.LOG_SHOWOFF_FORMAT.'">(%s) %s</span> para el Pedido %s.');
  define('LOG_ERROR_8006' , 'El fichero de Cliente %s ya existe. El Pedido %s no se descargará.');
  define('LOG_ERROR_8007' , 'No se ha creado un fichero de Cliente <span style="'.LOG_SHOWOFF_FORMAT.'">(%s) %s</span>. El Pedido <b>%s</b> no se descargará.');
  define('LOG_INFO_8010'  , 'El <span style="'.LOG_SHOWOFF_FORMAT.'">Pedido %s</span> no se ha descargado. ');
  define('LOG_INFO_8012'  , 'El País del Cliente no coincide.');
  define('LOG_INFO_8013'  , 'La Dirección de Entrega del Pedido es diferente de la Dirección Principal del Cliente.');
  define('LOG_ERROR_8022' , 'El Producto <span style="'.LOG_SHOWOFF_FORMAT.'">(%s) %s</span> no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.');
  define('LOG_AVISO_8022' , 'El Producto <span style="'.LOG_SHOWOFF_FORMAT.'">(%s) %s</span> no se ha hallado correspondencia en FactuSol (en el Pedido: <b>%s</b>).');
  define('LOG_AVISO_8023' , 'El Producto <span style="'.LOG_SHOWOFF_FORMAT.'">(%s) %s</span> no se ha hallado correspondencia en FactuSol y tiene un valor no nulo en el campo "Referencia" %s (en el Pedido: <b>%s</b>).');
  define('LOG_ERROR_8025' , 'El Producto <span style="'.LOG_SHOWOFF_FORMAT.'">(%s) %s</span> tiene un Impuesto que no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.');
  define('LOG_ERROR_8028' , 'El Coste de Envío del Pedido <span style="'.LOG_SHOWOFF_FORMAT.'">%s</span> tiene un Impuesto que no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.');
  define('LOG_ERROR_8029' , 'El Coste de Contra-Reembolso del Pedido <span style="'.LOG_SHOWOFF_FORMAT.'">%s</span> tiene un Impuesto que no se ha hallado correspondencia en FactuSol. El Pedido <b>%s</b> no se descargará.');
  define('LOG_AVISO_8032' , 'La Forma de Pago del Pedido <span style="'.LOG_SHOWOFF_FORMAT.'">%s</span> (%s) no se ha hallado correspondencia en FactuSol. Deberá ponerla manualmente en FactuSol.');
  define('LOG_ERROR_8101' , 'No se pueden descargar Pedidos. La carpeta destino no tiene permisos de escritura (%s).');
  define('LOG_ERROR_8106' , 'El fichero %s ya existe. El Pedido <b>%s</b> no se ha descargado.');
  define('LOG_ERROR_8107' , 'No se ha podido crear un fichero de Pedido %s. El Pedido <b>%s</b> no se ha descargado.');
  define('LOG_ERROR_8110' , 'No se ha podido borrar el fichero de Pedido %s, deberá borrarlo manualmente. El Pedido <b>%s</b> no se ha descargado.');

