<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLogger extends Model
{
    // See: https://github.com/afrittella/laravel-loggable/blob/master/src/database/migrations/2017_07_05_191642_create_logs_table.php
    // https://docs.spatie.be/laravel-activitylog/v2/introduction

    const TIMER = 0;
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    public $levels = [
        self::TIMER => 'TIMER',
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::NOTICE => 'NOTICE',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR',
        self::CRITICAL => 'CRITICAL',
        self::ALERT => 'ALERT',
        self::EMERGENCY => 'EMERGENCY'
    ];
    protected $timer_start;
    protected $timer_stop;
    protected $timer_total;

    private $loggable_model;
//    public $log_name = 'default';
//    public $description = '';

    private $write_to; //  = 'database';
    public  $msg_queue = array();
    
    protected $casts = [
        'context' => 'array',
    ];

    protected $fillable = [ 'log_name', 'description', 'level', 'level_name', 'message','context', 'user_id', 'date_added', 'secs_added' ];

    
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

//    public function __construct( $loggable_model = null, $write_to = 'database' ) 
    public function __construct( $log_name = 'default', $write_to = 'database' ) 
    {
      // 
      // $this->loggable_model = $loggable_model;
      if ( $log_name ) {
//      		if ( is_object( $log_name ) && !method_exists( $log_name, '__toString' ) ) 
      		$this->log_name = $log_name;
      }  // else -> use 'default' value

      $this->description = '';
      
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

      $this->log("TIMER", LOG_TIMER_1000);
    }

    public function timer_stop($display = 'false') 
    {
      $this->timer_stop = microtime();

      $this->log("TIMER", LOG_TIMER_1010);

      $time_start = explode(' ', $this->timer_start);
      $time_end   = explode(' ', $this->timer_stop);

      $this->timer_total = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

      $this->log("TIMER", 'Control de tiempos '.$this->timer_total . 's');

      if ($display != 'false') {
        return $this->timer_display();
      }
    }

    public function timer_display() 
    {
      $p = "$this->timer_start  - $this->timer_stop";
      return '<span class="smallText">'.$p.' :: Parse Time: ' . $this->timer_total . 's</span>';
    }

    public function addLog($level = 0, $message = '', $context = [])
    {
        if ( !$message && !$level ) $message="Unknown error";

        if (!empty($message)) {
            $message = $this->interpolate($message, $context);
        }

        $level_name = is_string( $level ) ? $level : $this->getLevelName($level);

        $temp = explode(" ", microtime());
        $date_added = date('Y-m-d H:i:s', $temp[1]);
        $secs_added = substr($temp[0], 2, 6);

        $user_id = \Auth::check() ? \Auth::user()->id : null;

        $log_data = [
           'log_name' => $this->log_name,
           'description' => $this->description,
           'level' => $level,
           'level_name' => $level_name,
           'message' => $message,
           'context' => $context,
           'user_id' => $user_id,
           'date_added' => $date_added,
           'secs_added' => $secs_added,
        ];


	    if ($this->write_to == 'database')
	    {  

	        $log_record = new ActivityLogger();
	        $log_record->fill($log_data);

	        //@TODO manage exceptions
//	        $log_record->loggable()->associate($this->loggable_model);

	        $log_record->save();

	    } else {

	          $this->msg_queue[] = $log_data;
	    }
    }

    public function start() 
    {
          $this->log("INFO", LOG_INFO_1000);
    }

    public function reset() 
    {
          // Table name: ((new self)->getTable());     or   $item->getTable();
//          abi_r($this->log_name);

          $log = ActivityLogger::where('log_name', $this->log_name)->get();

//          abi_r($log);

          $log->each(function($item) {
                $item->delete();
            });

          $this->log("INFO", LOG_INFO_1010);
    }

    public function empty() 
    {
          ActivityLogger::truncate();
    }

    public function stop() 
    {
          $this->log("INFO", LOG_INFO_1020);
    }



    public function log($level = '', $message = '', $context = [])
    {
        $this->addLog($level, $message, $context);
    }

    /**
     * Return array with level-names => level-codes
     *
     * @return array
     */
    public function getLevels(): array
    {
        return array_flip($this->levels);
    }


    public function getLevelName(int $level): string
    {
        if (!isset($this->levels[$level])) {
            // throw new InvalidArgumentException('Level "' . $level . '" is not defined.');
            return 'unknown';
        }

        return $this->levels[$level];
    }


    public function toLoggerLevel($level): int
    {
        if (is_string($level)) {
            if (defined(__CLASS__.'::'.strtoupper($level))) {
                return constant(__CLASS__.'::'.strtoupper($level));
            }
            // throw new InvalidArgumentException('Level "'.$level.'" is not defined, use one of: '.implode(', ', array_keys($this->levels)));
            return 0;
        }
        return $level;
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    public function interpolate($message, $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */


    public function scopeFilter($query, $params)
    {
        if ( isset($params['log_name']) && $params['log_name'] !== '' )
        {
            $query->where('log_name', $params['log_name']);
        }

        return $query;
    }



    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function loggable()
    {
        return $this->morphTo();
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


  define('LOG_TIMER_1000' , 'Control de Tiempos iniciado');
  define('LOG_TIMER_1010' , 'Control de Tiempos terminado');


