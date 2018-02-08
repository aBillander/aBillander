<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Context extends Model 
{
	/**
	 * @var Context
	 */
	protected static $instance;

	/**
	 * @var Employee
	 */
	public $user;			// $employee;

	/**
	 * @var AdminTab
	 */
	public $company;

	/**
	 * @var Country
	 */
	public $country;

	/**
	 * @var Language
	 */
	public $language;

	/**
	 * @var Currency
	 */
	public $currency;

	/**
	 * @var Controller
	 */
	public $controller;

	/**
	 * @var Cookie
	 */
	public $cookie;

	/**
	 * @ var Mobile Detect (Future use)
	 */
	public $mobile_detect;

	/**
	 * @var boolean|string mobile device of the customer (Future use)
	 */
	protected $mobile_device = null;
	
	const DEVICE_COMPUTER = 1;
	
	const DEVICE_TABLET = 2;
	
	const DEVICE_MOBILE = 4;


	/**
	 * Get a singleton context
	 *
	 * @return Context
	 */
	public static function getContext()
	{
		if (!isset(self::$instance))
			self::$instance = new Context();
		return self::$instance;
	}
	
	/**
	 * Clone current context
	 * 
	 * @return Context
	 */
	public function cloneContext()
	{
		return clone($this);
	}
}
