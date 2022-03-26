<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\ProductionSheet;
use App\Models\ProductionSheetNew;

use App\Models\ProductionSheetRegistry;

// php artisan make:provider ProductionSheetServiceProvider

// Registry Pattern
// http://rizqi.id/laravel-registry-pattern
// https://stackoverflow.com/questions/36853791/laravel-dynamic-dependency-injection-for-interface-based-on-user-input

class ProductionSheetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->make(ProductionSheetRegistry::class)
                ->register("legacy", new ProductionSheet());

        $this->app->make(ProductionSheetRegistry::class)
                ->register("new", new ProductionSheetNew());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(ProductionSheetRegistry::class);
    }
}
