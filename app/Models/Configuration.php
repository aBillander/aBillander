<?php

namespace App\Models;

// Check this: http://www.qcode.in/save-laravel-app-settings-in-database/

use Illuminate\Database\Eloquent\Model;
use aBillander\Installer\Helpers\Installer;

class Configuration extends Model
{
    /**
     * The aBillander Application version.
     *
     * @var string
     */
    const VERSION = '9.2.22';


    protected $fillable = [ 'name', 'value', 'description' ];

	public static $rules = [
        'name' => 'required',
    ];

	/** @var array Configuration cache */
	protected static $_CONF;


	public static function loadConfiguration()
	{
		if (Installer::alreadyInstalled())
		if ( \Schema::hasTable('configurations') ) {

			self::$_CONF = array();

			try {

				$results = Configuration::All();
				
			} catch (\Illuminate\DataBase\QueryException $e) {

				abort(503, l('Cannot stablish a database connection :: Configurations are not loaded.', 'layouts'));

				// Die (not so) silently:
				echo 'Cannot stablish a database connection :: Configurations are not loaded.';
				die();
				
			}

			if ($results->count())
				foreach ($results as $result)
				{
					self::$_CONF[$result->name] = $result->value;
				}
		
		} else {

				// Die (not so) silently:
				echo 'Cannot stablish a database connection :: Configurations are not loaded.';
				die();

		}
	}

	/**
	  * Update configuration key and value into database (automatically insert if key does not exist)
	  *
	  * @param string $key Key
	  * @param mixed $values $values is an array if the configuration is multilingual, a single string else.
	  * @param boolean $html Specify if html is authorized in value
		*
	  * @return boolean Update result
	  */
	public static function updateValue($key, $values)
	{
		// Prevent NULL values
        // $values = is_null( $values ) ? '' : $values;
        if ( is_null( $values ) ) $values = '';

		if (!Configuration::isConfigName($key))
	 	 	return  false;

		$current_value = Configuration::get($key);

		/* Update classic values */

			/* If the current value exists but the _CONF_IDS[$key] does not, it mean the value has been set but not save, we need to add */
		 	// if ( $current_value !== false )
		 	if ( self::exists($key) )
		 	{
		 		/* Do not update the database if the current value is the same one than the new one */
				if ($values == $current_value)
					$result = true;
				else
				{
					$model = Configuration::where('name', '=', $key)->firstOrFail();
					$model->value = $values;

					if ($result=$model->save())
						self::$_CONF[$key] = $values;
				}
			}
			// If key does not exists, create it
			else
			{
				$result = self::_addConfiguration($key, $values);
				if ($result)
				{
					self::$_CONF[$key] = $values;   // Configuration::set($key, $values, $id_shop_group, $id_shop);
				}
			}

		return (bool)$result;
	}

	/**
	  * Check if a configuration key exists
	  *
	  * @param string $key Key
		*
	  * @return boolean result
	  */
	public static function exists($key)
	{
		// Prevent NULL values
		if (!Configuration::isConfigName($key))
	 	 	return  false;

		if (!self::$_CONF)
		{
			Configuration::loadConfiguration();
		}
		if (isset(self::$_CONF[$key]))
			return true;
		return false;

		// return Configuration::where('name', $key)->exists();
	}

	/**
	  * Get a single configuration value (in one language only)
	  *
	  * @param string $key Key wanted
	  * @param integer $id_lang Language ID
	  * @return string Value
	  */
	public static function get($key, $id_lang = null)
	{
		if (!self::$_CONF)
		{
			Configuration::loadConfiguration();
		}
		if (isset(self::$_CONF[$key]))
			return self::$_CONF[$key];
		return false;
	}

	/**
	  * Get several configuration values
	  *
	  * @param array $keys Keys wanted
	  * @param integer $id_lang Language ID
	  * @return array Values
	  */
	public static function getMultiple($keys)
	{
		$resTab = array();

	 	if ( !is_array($keys) || !is_array(self::$_CONF) )
	 		return $resTab;

		foreach ($keys as $key)
			if (array_key_exists($key, self::$_CONF))
				$resTab[$key] = self::$_CONF[$key];

		return $resTab;
	}

	// Helper function
	public static function isTrue($key)
	{
		return ( intval( Configuration::get($key) ) > 0 );
	}

	// Helper function
	public static function isFalse($key)
	{
		return !Configuration::isTrue($key);
	}

	// Helper function
	public static function getInt($key)
	{
		return intval(Configuration::get($key));
	}

	// Helper function
	public static function getNumber($key)
	{
		return floatval(Configuration::get($key));
	}

	// Helper function
	public static function getString($key)
	{
		return strval(Configuration::get($key));
	}

	// Helper function
	public static function isEmpty($key)
	{
		return trim((string) Configuration::get($key)) === '';

//		return strlen(trim(Configuration::get($key))) === 0;

//		return ! is_bool($value) && ! is_array($value) && trim((string) $value) === '';
	}

	// Helper function
	public static function isNotEmpty($key)
	{
		return !Configuration::isEmpty($key);
	}

	/**
	  * Set TEMPORARY a single configuration value
	  *
	  * @param string $key Key wanted
	  * @param mixed $values $values is an array if the configuration is multilingual, a single string else.
		*
	  */
	public static function set($key, $values)
	{
		// if (!Validate::isConfigName($key))
	 	//	die(Tools::displayError());

	 	/* Update classic values */
		self::$_CONF[$key] = $values;
	}

	/**
	  * Insert configuration key and value into database
	  *
	  * @param string $key Key
	  * @param string $value Value
	  * @eturn boolean Insert result
	  */
	protected static function _addConfiguration($key, $value = null)
	{
		$newConfig = new Configuration();
		$newConfig->name = $key;
		if (!is_null($value))
			$newConfig->value = $value;
		return $newConfig->save() ? intval( $newConfig->id ) : false;
	}

	/**
	  * Delete a configuration key in database
	  *
	  * @param string $key Key to delete
	  * @return boolean Deletion result
	  */
	public static function deleteByName($key)
	{
		// If the key is invalid or if it does not exists, do nothing.
	 	// if (!Validate::isConfigName($key))
		// 	return false;

		$model = Configuration::where('name', '=', $key)->first();
		if ($model && $model->delete())
			unset(self::$_CONF[$key]);
		else
			return false;

		return true;
	}

	/**
	 * Check for configuration key validity
	 *
	 * @param string $config_name Configuration key to validate
	 * @return boolean Validity is ok or not
	 */
	public static function isConfigName($config_name)
	{
		return preg_match('/^[a-zA-Z_0-9-]+$/', $config_name);
	}
}
