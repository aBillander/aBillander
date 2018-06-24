<?php

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