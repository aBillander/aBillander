<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        // some other mapping actions

        $this->mapAbccRoutes();

        $this->mapAbsrcRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
//        Route::domain( env('ABI_DOMAIN') )
//             ->middleware('web')
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the Customer Center routes of the application.
     *
     *
     * @return void
     */
    protected function mapAbccRoutes()
    {
    /*
        Route::prefix('v1')  // if you need to specify a route prefix
            ->middleware('auth:api') // specify here your middlewares
            ->namespace($this->namespace) // leave it as is
            / ** the name of your route goes here: ** /
            ->group(base_path('routes/users.php'));
    */

        if ( \App\Configuration::isFalse('ENABLE_CUSTOMER_CENTER') ) return;

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/abcc.php'));

        // Maybe need: php artisan config:clear
    }

    /**
     * Define the Customer Center routes of the application.
     *
     *
     * @return void
     */
    protected function mapAbsrcRoutes()
    {
        
        if ( \App\Configuration::isFalse('ENABLE_SALESREP_CENTER') ) return;

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/absrc.php'));

        // Maybe need: php artisan config:clear
    }
}
