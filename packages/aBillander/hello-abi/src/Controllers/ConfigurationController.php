<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

use aBillander\Installer\Helpers\EnvironmentManager;

class ConfigurationController extends Controller
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'APP_URL' => 'required|url',
        'DB_HOST' => 'required|ip',
        'DB_PORT' => 'required|integer',
        'DB_DATABASE' => 'required|string',
        'DB_USERNAME' => 'required|string',
        'DB_PASSWORD' => 'required|string',
    ];

    /**
     * Display the installer configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('installer::configuration');
    }

    /**
     * Save the configuration.
     *
     * @param  Request  $request
     * @param  EnvironmentManager  $environmentManager
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, EnvironmentManager $environmentManager)
    {
        $request->validate($this->rules);

        // Save the config in the .env file
        $databaseInputs = array_keys($this->rules);
        $environmentNewValues = $request->only($databaseInputs);

        $environmentManager->setValues($environmentNewValues);

        // Check if the credentials of the database are valid
        try {
            DB::purge('mysql');
            DB::connection()->getPdo();
        }
        catch (\PDOException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('installer::install');
    }
}
