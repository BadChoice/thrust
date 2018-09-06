<?php

namespace BadChoice\Thrust;

use Illuminate\Support\ServiceProvider;

class ThrustServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'thrust');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'thrust');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/../config/thrust.php' => config_path('thrust.php')
        ], 'config');

        app(ResourceManager::class)->boot();
    }

    public function register() {
        app()->singleton(ResourceManager::class , function(){
            return new ResourceManager();
        });
    }

}