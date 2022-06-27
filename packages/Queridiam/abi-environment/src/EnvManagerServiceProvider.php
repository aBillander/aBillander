<?php

namespace Queridiam\EnvManager;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class EnvManagerServiceProvider extends IlluminateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Routes
        $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');

        // Translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'envmanager');
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/aBillander/envmanager'),
        ]);

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'envmanager');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->make('aBillander\EnvManager\Controllers\EnvManagerController');
    }
}
