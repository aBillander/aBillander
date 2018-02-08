<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
