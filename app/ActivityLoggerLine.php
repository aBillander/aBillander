<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLoggerLine extends Model
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

    private $loggable_model;

    
    protected $casts = [
        'context' => 'array',
    ];

    protected $fillable = [ 'level', 'level_name', 'message','context', 'user_id', 'date_added', 'secs_added' ];


    
    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function addLog($level = 0, $message = '', $context = [])
    {
        $record = new ActivityLoggerLine();

        if ( !$message && !$level ) $message="Unknown error";

        if (!empty($message)) {
            $message = $record->interpolate($message, $context);
        }

        $level_name = is_string( $level ) ? $level : $record->getLevelName($level);

        $temp = explode(" ", microtime());
        $date_added = date('Y-m-d H:i:s', $temp[1]);
        $secs_added = substr($temp[0], 2, 6);

        $user_id = \Auth::check() ? \Auth::user()->id : null;

        $log_data = [
           'level' => $record->toLoggerLevel($level),
           'level_name' => $level_name,
           'message' => $message,
           'context' => $context,
           'date_added' => $date_added,
           'secs_added' => $secs_added,
        ];


        $record->fill($log_data);

	        //@TODO manage exceptions
//	        $log_record->loggable()->associate($this->loggable_model);

        $record->save();

        return $record;

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
                $replace[':' . $key      ] = $val;          // Laravel Style
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

    public function activitylogger()
    {
        return $this->belongsTo('App\ActivityLogger');
    }
}

