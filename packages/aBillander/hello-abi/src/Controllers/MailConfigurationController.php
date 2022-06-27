<?php

namespace aBillander\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

use Mail;

use aBillander\Installer\Helpers\EnvironmentManager;

class MailConfigurationController extends Controller
{
    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [
        'MAIL_MAILER' => 'required',
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

        // Sanitize MAIL_FROM_NAME
        if ( strpos($environmentNewValues['MAIL_FROM_NAME'], ' ') !== FALSE )
            $environmentNewValues['MAIL_FROM_NAME'] = '"'.$environmentNewValues['MAIL_FROM_NAME'].'"';

        // abi_r($environmentNewValues);die();

        $environmentManager->setValues($environmentNewValues);


        if ( $request->has('action') && $request->action == 'check' )
        {
            // Check if the credentials of the email host are valid
            try {
                $language = app()->getLocale();
                $subject = __('installer::main.mail.subject');
                $message = __('installer::main.mail.message');

                $send = Mail::send('installer::emails.basic',
                    array(
                        'language' => $language,
                        'user_email'   => config('mail.from.address'),
                        'user_name'    => config('mail.from.name'),
                        'user_message' => $message,
                    ), function($message) use ( $subject )
                {
                    $message->from( config('mail.from.address'), config('mail.from.name') );
                    $message->to(   config('mail.from.address'), config('mail.from.name') )->subject( $subject );
                });

                return back()->with('success', __('installer::main.mail.check_ok'));
            }
            catch (\Exception $e) {
                return back()->with('error', [__('installer::main.mail.check_ko'), $e->getMessage()]);
            }
        } 
        else
            return redirect()->route('installer::install');

        // return redirect()->route('installer::install');
    }
}
