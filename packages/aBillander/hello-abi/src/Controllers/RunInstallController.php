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
        return view('installer::install');
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

        // Generate new application key
        Artisan::call("key:generate", ["--force"=> true]);

        // Reload the cache
        Artisan::call("config:cache");

        // Set symlinks
        Artisan::call("storage:link");

        return redirect()->route('installer::company');
    }
}
