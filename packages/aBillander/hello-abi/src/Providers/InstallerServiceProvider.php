<?php

namespace aBillander\Installer;

use Illuminate\Support\ServiceProvider;

class InstallerServiceProvider extends ServiceProvider
{
    // http://localhost/thehub/public/clear-cache
    // composer dump-autoload

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Loading routes file
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../../views', 'hello_abi');

        // Migrations
        // Will be loaded when executing: php artisan migrate
        // $this->loadMigrationsFrom(__DIR__ . '/../migrations');

/*
        $this->publishes([
            __DIR__ . '/migrations/migration_name.php' => base_path('database/migrations/migration_name.php'),
        ]);
        ^--Maybe inside register method <--runs OK
*/
        // php artisan vendor:publish [--force]
        // Should publish translation files also: woocommerce.php
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Devolver singleton de conexion según configuracion (ver Intervention/Image)
        // Amitav Roy
        // https://www.youtube.com/watch?v=SLeY-2IPEXk
        // https://www.youtube.com/watch?v=SLeY-2IPEXk&list=PLkZU2rKh1mT_UmFeEqZiJep_vrFwLyR07
        // Bitfumes
        // https://www.youtube.com/watch?v=H-euNqEKACA
    }
}
