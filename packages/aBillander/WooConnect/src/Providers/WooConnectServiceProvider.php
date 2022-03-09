<?php

namespace aBillander\WooConnect;

use Illuminate\Support\ServiceProvider;

class WooConnectServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/../../views', 'woo_connect');

        // Migrations
/*
        $this->publishes([
            __DIR__ . '/migrations/migration_name.php' => base_path('database/migrations/migration_name.php'),
        ]);
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
        // https://www.youtube.com/watch?v=f5jCS-PT99I
        // https://www.youtube.com/watch?v=upJlS7xn7bYcl
    }
}
