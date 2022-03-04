<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

use App\Models\Configuration;

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
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // ABCC
            if ( Configuration::isFalse('ENABLE_CUSTOMER_CENTER') )
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/abcc.php'));

            // ABSRC
            if ( Configuration::isFalse('ENABLE_SALESREP_CENTER') )
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/absrc.php'));

            // mCRM
            if ( Configuration::isFalse('ENABLE_MCRM') )
            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/web_crm.php'));

            // MFG
            if ( Configuration::isFalse('ENABLE_MANUFACTURING') )
            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/web_mfg.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
