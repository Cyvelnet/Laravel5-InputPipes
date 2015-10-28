<?php

namespace Cyvelnet\InputPipe;

use Illuminate\Support\ServiceProvider;

class InputPipeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pipes', function ($app) {
            return new Factory($app['request'], $app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['pipes'];
    }
}
