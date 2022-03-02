<?php
// Fuck!
// https://medium.com/@ntimyeboah/how-to-add-a-custom-route-file-in-laravel-93a087c62424

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

class AbccServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Loading routes file
        $this->loadRoutesFrom(__DIR__ . '/../../routes/abcc.php');

        // Load Views
//        $this->loadViewsFrom(__DIR__ . '/../../views', 'woo_connect');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
