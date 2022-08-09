<?php

namespace Queridiam\POS;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

use Queridiam\POS\Http\Middleware\SetPosContextMiddleware;

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
    public function boot(Router $router)
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


        // Middleware
//        $router->middlewareGroup('installer',[CanInstall::class, NegotiateLanguage::class]);
        $router->aliasMiddleware('poscontext', SetPosContextMiddleware::class);




        if (request()->has('lang')) {
            \App::setLocale(request()->get('lang'));
        }

        $asset_v = config('constants.asset_version', 1);
        View::share('asset_v', $asset_v);
        
        // Share the list of modules enabled in sidebar
        View::composer(
            ['pos::*'],
            function ($view) {
                $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

                $__is_pusher_enabled = false;   // isPusherEnabled();

                if (!Auth::check()) {
                    $__is_pusher_enabled = false;
                }

                $view->with('enabled_modules', $enabled_modules);
                $view->with('__is_pusher_enabled', $__is_pusher_enabled);
            }
        );

/* --
        View::composer(
            ['layouts.*'],
            function ($view) {
                if(isAppInstalled()){
                    $keys = ['additional_js', 'additional_css'];
                    $__system_settings = System::getProperties($keys, true);

                    //Get js,css from modules
                    $moduleUtil = new ModuleUtil;
                    $module_additional_script = $moduleUtil->getModuleData('get_additional_script');
                    $additional_views = [];
                    $additional_html = '';
                    foreach ($module_additional_script as $key => $value) {
                        if (!empty($value['additional_js'])) {
                            if (isset($__system_settings['additional_js'])) {
                                $__system_settings['additional_js'] .= $value['additional_js'];
                            } else {
                                $__system_settings['additional_js'] = $value['additional_js'];
                            }
                            
                        }
                        if (!empty($value['additional_css'])) {
                            if (isset($__system_settings['additional_css'])){
                                $__system_settings['additional_css'] .= $value['additional_css'];
                            } else {
                                $__system_settings['additional_css'] = $value['additional_css'];
                            }
                        }
                        if (!empty($value['additional_html'])) {
                            $additional_html .= $value['additional_html'];
                        }
                        if (!empty($value['additional_views'])) {
                            $additional_views = array_merge($additional_views, $value['additional_views']);
                        }
                    }
                    
                    $view->with('__additional_views', $additional_views);
                    $view->with('__additional_html', $additional_html);
                    $view->with('__system_settings', $__system_settings);
                }
            }
        );
-- */

        //Blade directive to display help text.
        Blade::directive('show_tooltip', function ($message) {
            return "<?php
                if(1||session('business.enable_tooltip')){
                    echo '<i class=\"fa fa-info-circle text-info hover-q no-print \" aria-hidden=\"true\" 
                    data-container=\"body\" data-toggle=\"popover\" data-placement=\"auto bottom\" 
                    data-content=\"' . $message . '\" data-html=\"true\" data-trigger=\"hover\"></i>';
                }
                ?>";
        });

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
