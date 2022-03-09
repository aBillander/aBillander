<?php

namespace Queridiam\WooCommerce;

use Automattic\WooCommerce\Client;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
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
        $this->publishes([
            __DIR__ . '/../config/woocommerce.php' => config_path('woocommerce.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/woocommerce.php',
            'woocommerce'
        );

        $config = $this->app['config']->get('woocommerce');

        $this->app->singleton('woocommerce.client', function () use ($config) {
            return new Client(
                $config['store_url'],
                $config['consumer_key'],
                $config['consumer_secret'],
                [
                    'version' => 'wc/' . $config['api_version'],
                    'verify_ssl' => $config['verify_ssl'],
                    'wp_api' => $config['wp_api'],
                    'query_string_auth' => $config['query_string_auth'],
                    'timeout' => $config['timeout'],
                ]
            );
        });

        $this->app->singleton('Queridiam\WooCommerce\API', function ($app) {
            return new API($app['woocommerce.client']);
        });

        $this->app->alias('Queridiam\WooCommerce\API', 'woocommerce');
    }
}
