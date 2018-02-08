<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model 
{
    protected $fillable = [ 'name', 'value', 'description' ];

	public static $rules = [
        'name' => 'required',
    ];

	/** @var array Configuration cache */
	protected static $_CONF;


	public static function loadConfiguration()
	{
		self::$_CONF = array();

		$results = Configuration::All();

		if ($results->count())
			foreach ($results as $result)
			{
				self::$_CONF[$result->name] = $result->value;
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
		if (!Configuration::isConfigName($key))
	 	 	return  false;

		$current_value = Configuration::get($key);

		/* Update classic values */
		
			/* If the current value exists but the _CONF_IDS[$key] does not, it mean the value has been set but not save, we need to add */
		 	if ( $current_value !== false )
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