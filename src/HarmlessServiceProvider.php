<?php

namespace Shincoder\Harmless;

use Illuminate\Support\ServiceProvider;
use Shincoder\Harmless\Factory;

class HarmlessServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['command.migrate:test'] = $this->app->share(
            function ($app) {
                $factory = new Factory($app['db'], $app['config']);

                return new Commands\MigrateTestCommand($factory);
            }
        );

        $this->commands(['command.migrate:test']);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'command.migrate:test',
        ];
    }
}
