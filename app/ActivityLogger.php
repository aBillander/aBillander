<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ActivityLoggerLine;

class ActivityLogger extends Model
{

    protected $timer_start;
    protected $timer_stop;
    protected $timer_total;


    protected $fillable = [ 'name', 'signature', 'description', 'user_id' ];

//    protected $appends = ['last_modified_at'];
    
    
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getLastModifiedAtAttribute()
    {
        $value = $this->activityloggerlines()
                ->latest('created_at')
                ->first(); // ->updated_at;

        // abi_r($value);

        // return $value; // ->created_at; // ->toDateString();

        if ( $value ) return $value->created_at;    //->toDateString();
        return null;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public static function setup( $log_name = 'default', $log_description = '', $log_signature = '' )
    {
      // https://stackoverflow.com/questions/28395855/how-to-create-constructor-with-optional-parameters
      // https://stackoverflow.com/questions/1699796/best-way-to-do-multiple-constructors-in-php

      $self = new self();

      // 

      $self->name = 'default';
      if ( $log_name ) {
//      		if ( is_object( $log_name ) && !method_exists( $log_name, '__toString' ) ) 
      		$self->name = $log_name;
      }  // else -> use 'default' value

      
      $self->description = $log_description;
      if ( !$log_description ) {
          $self->description = $self->name;
      }

      $self->user_id = \Auth::check() ? \Auth::user()->id : null;

      $self->signature = $log_signature;
      if ( !$log_signature ) {
          $self->signature = $self->description;
      }

      $self->signature = md5( $self->signature.' :: '.$self->user_id );

      if ( $al = ActivityLogger::where( 'signature', $self->signature)->first() ) return $al;

      $self->save();

      return $self;
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



    public function start() 
    {
          $this->log("INFO", LOG_INFO_1000);
    }

    public function reset() 
    {
          return ;

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
          // ActivityLogger::truncate();
      
          $this->activityloggerlines()->delete();
    }

    public function stop() 
    {
          $this->log("INFO", LOG_INFO_1020);

          // Avoid touch date on every log entry 
          $this->updated_at = $this->last_modified_at;
          $this->save();
    }



    public function log($level = '', $message = '', $context = [])
    {
        $record = ActivityLoggerLine::addLog($level, $message, $context);

        $this->activityloggerlines()->save($record);
    }
    
    // Some ALIAS functions:
    public function logInfo($message = '', $context = [])
    {
        $this->log('INFO', $message, $context);
    }
    
    public function logWarning($message = '', $context = [])
    {
        $this->log('WARNING', $message, $context);
    }
    
    public function logError($message = '', $context = [])
    {
        $this->log('ERROR', $message, $context);
    }
    
    public function logTimer($message = '', $context = [])
    {
        $this->log('TIMER', $message, $context);
    }




    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    
    public function activityloggerlines()
    {
        return $this->hasMany('App\ActivityLoggerLine', 'activity_logger_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

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

  define('LOG_INFO_1000' , ':_> LOG iniciado');
  define('LOG_INFO_1010' , ':_> LOG reiniciado');
  define('LOG_INFO_1020' , ':_> LOG terminado');

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


