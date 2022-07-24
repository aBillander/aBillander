<?php

namespace Queridiam\POS;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class POSServiceProvider extends IlluminateServiceProvider
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
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'pos');
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/Queridiam/POS'),
        ]);

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pos');

        // Assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/abi-pos'),
        ], 'abi-pos');
        // php artisan vendor:publish --tag=abi-pos --force
        // Copying directory [packages/Queridiam/abi-pos/resources/assets] to [public/vendor/abi-pos]

        // Migrations
        // Will be loaded when executing: php artisan migrate
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->make('Queridiam\POS\Controllers\POSController');
    }
}
