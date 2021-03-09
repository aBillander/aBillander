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
//        'DB_HOST' => 'required|ip',
        'DB_HOST' => 'required|string',
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
        $rules = $this->rules;

        // Check local installation
        if ( (strpos($request->input('APP_URL'), 'localhost') === false) || 
             (strpos($request->input('APP_URL'), '127.0.0.1') === false)    )
        {
            unset( $rules['DB_PASSWORD'] );         // Local installation maynot have password
        }


        $request->validate($rules);

        // Save the config in the .env file
        $databaseInputs = array_keys($this->rules);
        $environmentNewValues = $request->only($databaseInputs);

        $environmentNewValues['ABI_DOMAIN'] = $request->input('APP_URL');
        $environmentNewValues['ABCC_DOMAIN'] = $request->input('APP_URL');
        $environmentNewValues['ABSRC_DOMAIN'] = $request->input('APP_URL');

        $environmentManager->setValues($environmentNewValues);

        // Check if the credentials of the database are valid
        try {
            DB::purge('mysql');
            DB::connection()->getPdo();

            return redirect()->back()->with('success', __('installer::main.config.check_ok'));
        }
        catch (\PDOException $e) {
            return redirect()->back()->with('error', [__('installer::main.config.check_ko'), $e->getMessage()]);
        }

        // return redirect()->route('installer::install');
    }
}
