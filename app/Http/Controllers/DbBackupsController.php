<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use \Artisan;

use App\Configuration;

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

}
