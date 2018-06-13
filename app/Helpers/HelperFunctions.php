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


function abi_r($foo, $exit=false)
	{
		echo '<pre>';
		print_r($foo);
		echo '</pre>';

		if ($exit) die();
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


