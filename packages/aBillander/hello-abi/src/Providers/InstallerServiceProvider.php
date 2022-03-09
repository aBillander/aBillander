<?php

namespace aBillander\Installer;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

use aBillander\Installer\Middleware\CanInstall;
use aBillander\Installer\Middleware\NegotiateLanguage;
use aBillander\Installer\Middleware\RedirectIfNeedsInstallation;

class InstallerServiceProvider extends ServiceProvider
{
    // http://localhost/thehub/public/clear-cache
    // composer dump-autoload

    /**
     * Bootstrap any application services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // Config
        $this->publishes([
            __DIR__.'/../../config/installer.php' => config_path('installer.php'),
        ], 'config');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        // Translations
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'installer');
        $this->publishes([
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/aBillander/installer'),
        ]);

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'installer');
        // Not publish
        // $this->publishes([
        //     __DIR__.'/../resources/views' => base_path('resources/views/vendor/abillander/installer'),
        // ], 'views');

        // Assets
        $this->publishes([
            __DIR__.'/../../resources/views/assets' => public_path('assets/installer'),
        ], 'public');

        // Middleware
        $router->middlewareGroup('installer',[CanInstall::class, NegotiateLanguage::class]);
        $router->aliasMiddleware('redirectifneedsinstallation', RedirectIfNeedsInstallation::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Config
        $this->mergeConfigFrom(__DIR__.'/../../config/installer.php', 'installer');

        // Middleware
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->prependMiddleware('\aBillander\Installer\Middleware\RedirectIfNeedsInstallation::class');
    }
}
