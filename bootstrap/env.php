<?php

/*
|--------------------------------------------------------------------------
| Poor Man Multi Tennant
|--------------------------------------------------------------------------
|
| https://scotch.io/@stephenafamo/how-to-build-a-multi-tenant-site-with-laravel
|
| Required by /public/index.php
|
*/

	
	// Extract the subdomain from URL.
	list($subdomain) = explode('.', $_SERVER['HTTP_HOST'], 2);

	if ( $subdomain && ($subdomain != 'localhost'))
	{
	    $env_dir = __DIR__.'/../';

	    $env_file = '.env-' . $subdomain;

	    // clearstatcache ( );  ???

	    if ( file_exists($env_dir.$env_file) )
	    {
	        try {
	        	$dotenv = new \Dotenv\Dotenv($env_dir, $env_file);
	        	$dotenv->load();
	        	
	        } catch (\Exception $e) {
	        	// echo $e->getMessage();
	        }
	    }

	}
 
 /*   
    $host = $_SERVER['HTTP_HOST'];

    if ( $host && (strpos($host, '-test') !== false) ) 
	{
	    $env_dir = __DIR__.'/../';

	    $env_file = '.env-test';

	    if (file_exists($env_dir.$env_file));
	    {
	        $dotenv = new \Dotenv\Dotenv($env_dir, $env_file);
	        $dotenv->load();
	    }
	}
*/