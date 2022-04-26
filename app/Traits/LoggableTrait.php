<?php 

namespace App\Traits;

use App\Models\ActivityLogger;

trait LoggableTrait
{
    // Logger to send messages
    protected $logger;
    protected $log_has_errors = false;

    /**
    * Return 
    */


    public static function loggerName() 
    {
        return __CLASS__;
    }

    public static function loggerSignature() 
    {
        return __CLASS__;
    }

    /**
    * Set up Logger 
    */
    public static function loggerSetup( $name = '', $signature = '', $back_to = '' ) 
    {
        if ( !$name)      $name      = self::loggerName();
        if ( !$signature) $signature = self::loggerSignature();

        return ActivityLogger::setup( $name, $signature, $back_to );
    }

    /**
    * Get Logger
    */
    public static function logger() 
    {
        $obj = new self();

        return $obj->logger;
    }


/* ********************************************************************************************* */   


    
    protected function logMessage($level = '', $message = '', $context = [])
    {
        $this->logger->log($level, $message, $context);

        if ( $level == 'ERROR' ) $this->log_has_errors = true;
    }
    
    public function logHasErrors()
    {
        return $this->log_has_errors; 
    }
    
    // Some ALIAS functions:
    public function logInfo($message = '', $context = [])
    {
        $this->logMessage('INFO', $message, $context);
    }
    
    public function logWarning($message = '', $context = [])
    {
        $this->logMessage('WARNING', $message, $context);
    }
    
    public function logError($message = '', $context = [])
    {
        $this->logMessage('ERROR', $message, $context);
    }
    
    public function logTimer($message = '', $context = [])
    {
        $this->logMessage('TIMER', $message, $context);
    }

}