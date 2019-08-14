<?php
	
	// Extract the subdomain from URL.
	list($subdomain) = explode('.', $_SERVER['HTTP_HOST'], 2);

	if ( $subdomain && ($subdomain != 'localhost'))
	{
	    $env_dir = __DIR__.'/../';

	    $env_file = '.env-' . $subdomain;

	    // clearstatcache ( );  ???

	    if ( file_exists($env_dir.$env_file) );		// Somohow returs always true, no matter the value of $domain . Why?
	    {
	        try {
	        	$dotenv = new \Dotenv\Dotenv($env_dir, $env_file);
	        	$dotenv->load();
	        	
	        } catch (Exception $e) {
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