<?php

/*
|--------------------------------------------------------------------------
| Helper functions.
|--------------------------------------------------------------------------
|
| ToDo: move to a proper location if necessary; Google this: 
| "laravel 5 where to put helpers".
|
*/

function l($string = NULL, $data = [], $langfile = NULL)
	{
        if ( is_string($data) && ($langfile == NULL) ) {
            $langfile = $data;
            $data = [];
        }

        if ($langfile == NULL) $langfile = \App\Context::getContext()->controller;

        if (Lang::has('localized/'.$langfile.'.'.$string))
            return Lang::get('localized/'.$langfile.'.'.$string, $data);

        if (Lang::has($langfile.'.'.$string))
            return Lang::get($langfile.'.'.$string, $data);
	//	elseif (Lang::has('_allcontrollers.'.$string))
	//		return Lang::get('_allcontrollers.'.$string);
		else 
		{
			foreach ($data as $key => $value)
			{
				$string = str_replace(':'.$key, $value, $string);
			}
			return $string;
		}
	}


function abi_yn_label($foo=true)
  {
    if ( (bool) $foo ) return l('Yes', [], 'layouts');

    return l('No', [], 'layouts');
  }




function abi_r($foo, $exit=false)
  {
    echo '<pre>';
    print_r($foo);
    echo '</pre>';

    if ($exit) die();
  }


function abi_toSql($query)
  {
    dd($query->toSql(), $query->getBindings());
  }


function abi_date_short(\Carbon\Carbon $date = null, $format = '')
    {
        if (!$date) return null;

        // http://laravel.io/forum/03-11-2014-date-format
        // https://laracasts.com/forum/?p=764-saving-carbon-dates-from-user-input/0

        // if ($format == '') $format = \App\Configuration::get('DATE_FORMAT_SHORT');     
        if ($format == '') $format = \App\Context::getContext()->language->date_format_lite; // Should take value after User / Environment settings
        if (!$format) $format = \App\Configuration::get('DATE_FORMAT_SHORT');
        // echo ($format); die();
        // $date = \Carbon\Carbon::createFromFormat($format, $date);    
        // http://laravel.io/forum/03-12-2014-class-carbon-not-found?page=1

        // echo $date.' - '.Configuration::get('DATE_FORMAT_SHORT').' - '.$date->format($format); die();

        return $date->format($format);
    }

function abi_toLocale_date_short(\Carbon\Carbon $date = null, $format = '')
    {
        if (!$date) return null;

        if ($format == '') $format = \App\Context::getContext()->language->date_format_lite;

        if (!$format) $format = \App\Configuration::get('DATE_FORMAT_SHORT');

        return $date->format($format);
    }

function abi_fromLocale_date_short($date_form = null, $format = '')
    {
        if (!$date_form) return null;

        if ($format == '') $format = \App\Context::getContext()->language->date_format_lite;

        try {
            $date = \Carbon\Carbon::createFromFormat( $format, $date_form );
        }
        catch ( Exception $e ){
            return null;
        }

        return $date->toDateString();
    }

function abi_date_form_short($str_date = '', $format = '')
    {
        if (!$str_date) return null;

        if ($str_date == 'now') 
            $date = \Carbon\Carbon::now();
        else {
            try {
                    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $str_date);
            }
            catch ( Exception $e ){   
                echo $e->getMessage();
                return null;
            }
        }

        // http://laravel.io/forum/03-11-2014-date-format
        // https://laracasts.com/forum/?p=764-saving-carbon-dates-from-user-input/0

        // if ($format == '') $format = \App\Configuration::get('DATE_FORMAT_SHORT');     
        if ($format == '') $format = \App\Context::getContext()->language->date_format_lite; // Should take value after User / Environment settings
        if (!$format) $format = \App\Configuration::get('DATE_FORMAT_SHORT');
        // echo ($format); die();
        // $date = \Carbon\Carbon::createFromFormat($format, $date);    
        // http://laravel.io/forum/03-12-2014-class-carbon-not-found?page=1

        // echo $date.' - '.Configuration::get('DATE_FORMAT_SHORT').' - '.$date->format($format); die();

        return $date->format($format);
    }

function abi_form_date_short($date_form = null)
    {
        if (!$date_form) return null;

        $date = \Carbon\Carbon::createFromFormat( \App\Context::getContext()->language->date_format_lite, $date_form );

        return $date->toDateString();
    }

function abi_date_form_full($str_date_time = '', $format = '')
    {
        if (!$str_date_time) return null;

        list($str_date, $time) = explode(' ', $str_date_time);

        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $str_date);

        // http://laravel.io/forum/03-11-2014-date-format
        // https://laracasts.com/forum/?p=764-saving-carbon-dates-from-user-input/0

        // if ($format == '') $format = \App\Configuration::get('DATE_FORMAT_SHORT');     
        if ($format == '') $format = \App\Context::getContext()->language->date_format_lite; // Should take value after User / Environment settings
        if (!$format) $format = \App\Configuration::get('DATE_FORMAT_SHORT');
        // echo ($format); die();
        // $date = \Carbon\Carbon::createFromFormat($format, $date);    
        // http://laravel.io/forum/03-12-2014-class-carbon-not-found?page=1

        // echo $date.' - '.Configuration::get('DATE_FORMAT_SHORT').' - '.$date->format($format); die();

        return $date->format($format) . ' ' . $time;
    }



function abi_mail_from_name()
{
    // \App\Context::getContext()->user->getFullName();

    return config('mail.from.name');
}

function abi_mail_from_address()
{
    // \App\Context::getContext()->user->email;

    return config('mail.from.address');
}

function abi_money($amount, \App\Currency $currency = null)
{
    if (!is_numeric($amount))
        return $amount;

    if ($currency === null)
        $currency = \App\Context::getContext()->currency;

    $number = number_format($amount, $currency->decimalPlaces, $currency->decimalSeparator, $currency->thousandsSeparator);

    $blank = $currency->blank ? ' ' : '';
    if ( $currency->signPlacement > 0 )
        $number = $number . $blank . $currency->sign;
    else
        $number = $currency->sign . $blank . $number;
    
    // NOTE: negative amounts may require additional formatting for negative sign: -100 / 100- / (100)

    return $number;
}

function abi_money_amount($amount, \App\Currency $currency = null)
{
    if (!is_numeric($amount))
        return $amount;

    if ($currency === null)
        $currency = \App\Context::getContext()->currency;

    $number = number_format($amount, $currency->decimalPlaces, $currency->decimalSeparator, $currency->thousandsSeparator);
    
    // NOTE: negative amounts may require additional formatting for negative sign: -100 / 100- / (100)

    return $number;
}


function abi_amount( $val = 0.0, $decimalPlaces = 0 )
{
    $data = floatval( $val );

    // Do formatting

    $data = number_format($data, $decimalPlaces, ',', '.');

    return $data;
}




/**
 * PHP Multi Dimensional Array Combinations.
 *
 * 
 */
function combos($data, &$all = array(), $group = array(), $val = null, $i = 0)
{
	if (isset($val))
	{
		array_push($group, $val);
	}

	if ($i >= count($data))
	{
		array_push($all, $group);
	}
	else
	{
		foreach ($data[$i] as $v)
		{
			combos($data, $all, $group, $v, $i + 1);
		}
	}

	return $all;
}

/**
 * https://gist.github.com/aphoe/26495499a014cd8daf9ac7363aa3b5bd
 * @param $route
 * @return bool
 */
function checkRoute($route='') {

	if ($route=='/') return true;

	$route=ltrim($route, '/');

    $routes = \Route::getRoutes()->getRoutes();
    foreach($routes as $r){
//        if($r->getUri() == $route){
        if($r->uri() == $route){
            return true;
        }
    }

    return false;
}

/**
    * replacement for php's nl2br tag that produces more designer friendly html
    *
    * Modified from: http://www.php-editors.com/contest/1/51-read.html
    *
    * @param string $text
    * @param string $cssClass
    * @return string
*/
    function nl2p($text, $cssClass=''){

      // Return if there are no line breaks.
      if (!strstr($text, "\n")) {
         return $text;
      }

      // Return if text is HTML (simple check).
      // Etiquetas abren con < y cierran con </, excepto <br />
      if ( strstr($text, "<") && ( strstr($text, "</") || strstr($text, "/>") ) ) {
         return $text;
      } else if ( strstr($text, "<br>") || strstr($text, "<BR>") ) return $text;

      // Add Optional css class
      if (!empty($cssClass)) {
         $cssClass = ' class="' . $cssClass . '" ';
      }

      // put all text into <p> tags
      $text = '<p' . $cssClass . '>' . $text . '</p>';

      // replace all newline characters with paragraph
      // ending and starting tags
      $text = str_replace("\n", "</p>\n<p" . $cssClass . '>', $text);

      // remove empty paragraph tags & any cariage return characters
      $text = str_replace(array('<p' . $cssClass . '></p>', '<p></p>', "\r"), '', $text);

      return $text;

   } // end nl2p

  /**
    * expanding on the nl2p tag above to convert user contributed
    * <br />'s to <p>'s so it displays more nicely.
    *
    * @param string $text
    * @param string $cssClass
    * @return string
    */
    function br2p($text, $cssClass=''){

      // if (!eregi('<br', $text)) {  
      if (!preg_match('/<br/i', $text)) {
         return $text;
      }

      if (!empty($cssClass)) {
         $cssClass = ' class="' . $cssClass . '" ';
      }

      // put all text into <p> tags
      $text = '<p' . $cssClass . '>' . $text . '</p>';

      // replace all break tags with paragraph
      // ending and starting tags
      $text = str_replace(array('<br>', '<br />', '<BR>', '<BR />'), "</p>\n<p" . $cssClass . '>', $text);

      // remove empty paragraph tags
      $text = str_replace(array('<p' . $cssClass . '></p>', '<p></p>', "<p>\n</p>"), '', $text);

      return $text;
}

// https://laracasts.com/discuss/channels/laravel/human-readable-file-size-and-time
/**
     * Format bytes to kb, mb, gb, tb
     *
     * @param  integer $size
     * @param  integer $precision
     * @return integer
     */
    function abi_formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }



/*
|--------------------------------------------------------------------------
| Helper functions - Multi-tenancy.
|--------------------------------------------------------------------------
|
| Multi-tenancy 
| .
|
*/

if (! function_exists('abi_tenant_local_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function abi_tenant_local_path($path = '')
    {
        $tenant = \App\Context::getContext()->tenant;

        // return public_path( 'tenants/' , $tenant ).($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
        return '/tenants/' . $tenant . ($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}


if (! function_exists('abi_tenant_db_backups_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function abi_tenant_db_backups_path($path = '')
    {
        $tenant = \App\Context::getContext()->tenant;

        // return public_path( 'tenants/' , $tenant ).($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
        return 'db_backups/' . $tenant . ($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}


if (! function_exists('abi_safe_division')) {
    /**
     * Safe division for statistice & profitability.
     *
     * @param  
     * @return 
     */
    function abi_safe_division($a = 0.0, $b = 0.0)
    {
        if ( $b == 0.0 ) return 0.0;

        return $a / $b;
    }
}


