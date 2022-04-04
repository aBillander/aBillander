<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use \Artisan;

use App\Configuration;
use App\Tools;

use App\Events\DatabaseBackup;

class DbBackupsController extends Controller 
{

	public $default_MAX_DB_BACKUPS = 30;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$bk_folder = storage_path( abi_tenant_db_backups_path() );

		try {

			$listing =  array_reverse(
				\Arr::sort(File::files( $bk_folder ), function($file)
					{
					    return $file->getMTime();
					})
			);

		} catch (\Exception $e) {

	        return redirect()->route('home')
	                ->with('error', $e->getMessage());

        }

/*
		foreach ($listing as $v) {
			# code...
			abi_r($v->getFileName());
		}

		abi_r($listing, true);
*/

		$MAX_DB_BACKUPS = Configuration::get('MAX_DB_BACKUPS');
		$MAX_DB_BACKUPS_ACTION = Configuration::get('MAX_DB_BACKUPS_ACTION');

		$actions = [
					''       => l('Do nothing'),
					'delete' => l('Delete older Backups'),
					'email'  => l('Email warning'),
			];

		return view('db_backups.index', compact('bk_folder', 'listing', 'MAX_DB_BACKUPS', 'MAX_DB_BACKUPS_ACTION', 'actions'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function configurations()
    {
        return redirect()->route('dbbackups.index');

        // return view('db_backups.configurations');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function configurationsUpdate(Request $request)
    {
        // MAX_DB_BACKUPS_ACTION

        Configuration::updateValue('MAX_DB_BACKUPS', $request->input('MAX_DB_BACKUPS', $this->default_MAX_DB_BACKUPS));

        Configuration::updateValue('MAX_DB_BACKUPS_ACTION', $request->input('MAX_DB_BACKUPS_ACTION', ''));

        return redirect()->route('dbbackups.index')
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => ''], 'layouts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function job()
    {
        $job_url = url('/').'/dbbackup';
        $security_token = Configuration::isNotEmpty('DB_BACKUP_SECURITY_TOKEN') ?
        					Configuration::get('DB_BACKUP_SECURITY_TOKEN') :
        					strtoupper( Tools::passwdGen(16) );
        $cronUrl = $job_url.'?security_token='.$security_token;

        Configuration::updateValue('DB_BACKUP_SECURITY_TOKEN', $security_token);

        return view('db_backups.job_edit', compact('job_url', 'security_token', 'cronUrl'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function jobUpdate(Request $request)
    {
        $security_token = strtoupper( Tools::passwdGen(16) );

        Configuration::updateValue('DB_BACKUP_SECURITY_TOKEN', $security_token);

        return redirect()->back()
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $security_token], 'layouts'));
    }


	/**
	 * Process Backup.
	 *
	 * @return Response
	 */
	public function process()
	{
        if (!\function_exists('proc_open')) {
	        return redirect()->back()	// '/dbbackups')
	                ->with('error', l('The Data Base Backup class relies on proc_open, which is not available on your PHP installation.'));
        }

        try {

	    	// save it to the storage/backups/backup.sql file
            Artisan::call('db:backup');

        } catch (\Exception $e) {
            
	        return redirect()->back()	// '/dbbackups')
	                ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . $e->getMessage() . Artisan::output());

        }

	    // abi_r( Artisan::output() );

        $result = Artisan::output();
	    if (   strpos($result, l('Error', 'layouts')) !== false
			|| strpos($result, strtolower(l('Error', 'layouts'))) !== false )
		{
			$result = nl2p($result);

			return redirect()->back()	// '/dbbackups')
	                ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . $result);
		}

	    // The backup has been proceed successfully.
	    event(new DatabaseBackup());
	    
        return redirect()->back()	// '/dbbackups')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') . $result);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($filename)
    {
		//
		$bk_folder = storage_path( abi_tenant_db_backups_path() );

		$file = $bk_folder.'/'.$filename;

        if(is_file($file))
		{
		    // 1. possibility
		    // use Illuminate\Support\Facades\Storage;
		    // Storage::delete($file);
		    // 2. possibility
		    unlink( $file );
		}
		else
		{
		    // echo "File does not exist";
		}

        return redirect()->route(('dbbackups.index'))
                ->with('success', l('This record has been successfully deleted &#58&#58 (:id) ', ['id' => $filename], 'layouts'));
    }



    public function download($filename)
    {
		//
		$bk_folder = storage_path( abi_tenant_db_backups_path() );

		$file = $bk_folder.'/'.$filename;

        if(is_file($file))
		{
		    $headers = [

		              'Content-Type' => 'application/txt',

		           ];

		 

		return response()->download($file, $filename, $headers);
		}
		else
		{
		    // echo "File does not exist";
		}

	}

}

// https://www.google.com/search?client=ubuntu&channel=fs&q=xampp+mysqldump%3A+not+found&ie=utf-8&oe=utf-8
// https://stackoverflow.com/questions/22786583/mysqldump-command-not-found-xampp
// https://askubuntu.com/questions/979929/xampp-access-mysql-from-terminal