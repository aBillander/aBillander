<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use \Artisan;

use App\Configuration;
use App\Tools;

class DbBackupsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

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
		$bk_folder = storage_path().'/backups';

		$listing =  array_reverse( 
			array_sort(File::files( $bk_folder ), function($file)
				{
				    return $file->getMTime();
				})
		);

/*
		foreach ($listing as $v) {
			# code...
			abi_r($v->getFileName());
		}

		abi_r($listing, true);
*/

		return view('db_backups.index', compact('bk_folder', 'listing'));
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
        try {

	    	// save it to the storage/backups/backup.sql file
            Artisan::call('db:backup');

        } catch (\Exception $e) {
            
	        return redirect()->back()	// '/dbbackups')
	                ->with('error', l('Unable to create this record &#58&#58 (:id) ', ['id' => ''], 'layouts') . $e->getMessage() . Artisan::output());

        }

	    // abi_r( Artisan::output() );     // The backup has been proceed successfully.
	    
        return redirect()->back()	// '/dbbackups')
                ->with('success', l('This record has been successfully created &#58&#58 (:id) ', ['id' => ''], 'layouts') . Artisan::output());
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
		$bk_folder = storage_path().'/backups';

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
		$bk_folder = storage_path().'/backups';

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
