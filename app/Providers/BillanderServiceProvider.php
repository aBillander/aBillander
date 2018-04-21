<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Request;
use App\Configuration as Configuration;

class BillanderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Maybe modify here Laravel config array loaded from files...
        Configuration::loadConfiguration();


        /*
        if( \Auth::check() )
            $user = \App\User::find( Auth::id() );       // $email = Auth::user()->email;
        else
            $user = NULL;

        abi_r($user);    =>    null. User not available for service providers.
        abi_r('+++++++');
        // https://stackoverflow.com/questions/37555236/access-request-in-service-provider-after-applying-middleware
        // https://stackoverflow.com/questions/38764406/get-url-parameter-from-service-provider-in-laravel
        abi_r( $this->app->request->segment(1), true );
        // $this->app->request->route('slug')   to get the slug in the url
        */
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // The database connection is not available in register() function,
    }
}
