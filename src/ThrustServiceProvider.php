<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Console\Commands\Cache;
use BadChoice\Thrust\Console\Commands\Clear;
use BadChoice\Thrust\Console\Commands\Optimize;
use BadChoice\Thrust\Console\Commands\OptimizeClear;
use BadChoice\Thrust\Console\Commands\Prune;
use Illuminate\Support\ServiceProvider;

class ThrustServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'thrust');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'thrust');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/config/thrust.php' => config_path('thrust.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations/tenants')
        ], 'thrust-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Cache::class,
                Clear::class,
                Optimize::class,
                OptimizeClear::class,
                Prune::class,
            ]);
        }
    }

    public function register()
    {
        app()->singleton(ResourceManager::class, fn () => new ResourceManager);
        app()->singleton(ThrustObserver::class, fn () => new ThrustObserver);
    }
}
