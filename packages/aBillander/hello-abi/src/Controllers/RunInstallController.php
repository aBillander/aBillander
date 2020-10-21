<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use aBillander\Installer\Helpers\DatabaseManager;

class RunInstallController extends Controller
{
    /**
     * Display the install page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $tables = \DB::select('SHOW TABLES');
        
        return view('installer::install')->with(compact('tables'));
    }

    /**
     * Run the installation.
     *
     * @param  DatabaseManager  $databaseManager
     * @return \Illuminate\Http\Response
     */
    public function run(Request $request, DatabaseManager $databaseManager)
    {
        // Migrate and seed the database
        $response = $databaseManager->migrateAndSeed();

        if( $response['status'] == 'error' )
        {
            return back()->with('error', $response['message'])->with('info', nl2br($response['dbOutputLog']));
        }

        // Generate new application key
        Artisan::call("key:generate", ["--force"=> true]);

        // Reload the cache
        Artisan::call("config:cache");

        Artisan::call('route:clear');
        
        Artisan::call('view:clear');

        // Set symlinks
        // Warning: symlink(): permission denied
        try {
            
            // Artisan::call("storage:link");

            // Check web.php :: Route::get('/linkstorage', function () { ...

        } catch (Exception $e) {
            
        }

        return redirect()->route('installer::company')->with('info', nl2br($response['dbOutputLog']));
//                         ->with(['message' => $response]);
    }
}
