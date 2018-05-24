<?php

namespace Queridiam\FSxConnector;

use Illuminate\Support\ServiceProvider;

class FSxConnectorServiceProvider extends ServiceProvider
{
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
        $this->loadViewsFrom(__DIR__ . '/../../views', 'fsx_connector');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        // Register helpers. Maybe in register method?
        // Load files
        foreach (glob(__DIR__ . '/../Helpers/*.php') as $filename){
            require_once($filename);
        }

        // Translations
        // protected function loadTranslationsFrom($path, $namespace)
        // $this->loadTranslationsFrom(__DIR__.'/path/to/translations', 'courier');
        // See https://laravel.com/docs/5.5/packages

        // composer dump-autoload [-o] (optimize)

/*
        $this->publishes([
            __DIR__ . '/migrations/migration_name.php' => base_path('database/migrations/migration_name.php'),
        ]);
*/
        // php artisan vendor:publish [--force]
        // Should publish translation files also: fsx.php

        // Configuration
        $this->publishes([
            __DIR__.'/../config/fsxconnector.php' => config_path('fsxconnector.php'),
        ]);

        // Translations
        $this->publishes([
            __DIR__.'/../lang' => resource_path('lang'),
        ]);

        // Seeding
        $this->publishes([
            __DIR__.'/seeds' => database_path('seeds'),
        ]);

        // Assets
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/queridiam/fsxconnector'),
        ]);

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
        // https://www.youtube.com/watch?v=f5jCS-PT99I
        // https://www.youtube.com/watch?v=upJlS7xn7bYcl

        // https://www.youtube.com/watch?v=mhZa9oGLIw8&list=PLcgHShdyCyBMPTxEquVqNPeFm5KTnbvhk&index=3
        // https://www.youtube.com/watch?v=Xos8N8unKzQ
    }
}
