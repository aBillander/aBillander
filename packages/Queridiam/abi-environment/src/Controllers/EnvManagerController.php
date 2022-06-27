<?php

namespace Queridiam\EnvManager\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use aBillander\Installer\Helpers\EnvironmentManager;

class EnvManagerController extends Controller
{
   private $envPath;

   public $env_keys = [];

   public $envToConfig = [];

   public function __construct(EnvironmentManager $environmentManager)
   {
    /**
     * Set the full path to .env file.
     */
    $this->envPath = App::environmentFilePath();

    /**
     * The equivalence between .env and config keys.
     *
     * @var array
     */
    $this->envToConfig = EnvironmentManager::envToConfigTable();

    /**
     * Key Groups.
     *
     * @var array
     */
        $this->env_keys = [

                // SMTP mail keys
                1 => [

                        'MAIL_MAILER',      // 'smtp', since this is the "default" mailer
                        'MAIL_HOST',
                        'MAIL_PORT',
                        'MAIL_USERNAME',
                        'MAIL_PASSWORD',
                        'MAIL_ENCRYPTION',

                        'MAIL_FROM_ADDRESS',
                        'MAIL_FROM_NAME',
                    ],

                // WooCommerce shop keys
                2 => [

                        'WC_STORE_URL',
                        'WC_CONSUMER_KEY',
                        'WC_CONSUMER_SECRET',

                        'WC_VERIFY_SSL',
                        'WC_VERSION',
                        'WC_WP_TIMEOUT',

                        'WC_WEBHOOK_SECRET_PRODUCT_UPDATED',
                        'WC_WEBHOOK_SECRET_ORDER_CREATED',
                    ],
        ];

   }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function envkeys(Request $request)
    {
        $env_keys = array();
        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : 1;
        
        // Check tab_index
        $tab_view = 'envmanager::env_keys.'.'key_group_'.intval($tab_index);
        if (!\View::exists($tab_view)) 
            return \Redirect::to('404');

        $key_group = [];

        foreach ($this->env_keys[$tab_index] as $key)
        {
            $config_key = $this->envToConfig[$key] ?? '';
            if (!$config_key)
                continue ;

            $value = config($config_key);

            if ( $value === TRUE )
                $value = 'true';

            if ( $value === FALSE )
                $value = 'false';

            $key_group[$key] = $value;
        }

        return view( $tab_view, compact('tab_index', 'key_group') );
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function envkeysUpdate(Request $request, EnvironmentManager $environmentManager)
    {
        // To do:
        // $request->validate($this->rules);

        $tab_index =   $request->has('tab_index')
                        ? intval($request->input('tab_index'))
                        : -1;
        
        $key_group = isset( $this->env_keys[$tab_index] ) ?
                            $this->env_keys[$tab_index]   :
                            null ;

        // Check tab_index
        if (!$key_group) 
            return redirect('404');

        // Backup current .env file
        $envBK = $this->envPath.'_'.Carbon::now()->format('Y-m-d');
        // Only 1 backup per day
        if ( !file_exists( $envBK ) ){      // File::exists($path);
            copy($this->envPath, $envBK);   // File::copy(from_path, to_path);
        } else {
            $envBK = '';
        }

        // Save the config in the .env file
        $databaseInputs = $key_group;
        $environmentNewValues = $request->only($databaseInputs);

        // Sanitize some vars
        // MAIL_FROM_NAME
        if ( array_key_exists('MAIL_FROM_NAME', $environmentNewValues) )
        if ( strpos($environmentNewValues['MAIL_FROM_NAME'], ' ') !== FALSE )
            $environmentNewValues['MAIL_FROM_NAME'] = '"'.$environmentNewValues['MAIL_FROM_NAME'].'"';

        // WC_VERIFY_SSL
        if ( array_key_exists('WC_VERIFY_SSL', $environmentNewValues) )
        if ( $environmentNewValues['WC_VERIFY_SSL'] == 'true' || $environmentNewValues['WC_VERIFY_SSL'] == 'false' )
            ;
        else
            $environmentNewValues['WC_VERIFY_SSL'] = (bool) $environmentNewValues['WC_VERIFY_SSL'] ?
                                                                'true'  :
                                                                'false' ;

        // Ready for rock 'n roll?
        $environmentManager->setValues($environmentNewValues);

        return redirect('envmanager?tab_index='.$tab_index)
                ->with('success', l('This record has been successfully updated &#58&#58 (:id) ', ['id' => $tab_index], 'layouts').' <br />['.$envBK.']' );
    }
}