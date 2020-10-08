<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

use aBillander\Installer\Helpers\EnvironmentManager;

class MailConfigurationController extends Controller
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'MAIL_DRIVER' => 'required',
        'MAIL_HOST'   => 'required|string',
        'MAIL_PORT'   => 'required|integer',
        'MAIL_USERNAME'   => 'required|string',
        'MAIL_PASSWORD'   => 'required|string',
        'MAIL_ENCRYPTION' => 'required',

        'MAIL_FROM_ADDRESS' => 'required|email',
        'MAIL_FROM_NAME'    => 'required|string',
    ];

    /**
     * Display the installer configuration page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('installer::mail');
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

            return back()->with('success', __('installer::main.config.check_ok'));
        }
        catch (\PDOException $e) {
            return back()->with('error', [__('installer::main.config.check_ko'), $e->getMessage()]);
        }

        // return redirect()->route('installer::install');
    }
}
