<?php

namespace BadChoice\Thrust;

use Illuminate\Support\ServiceProvider;

class ThrustServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ .'/views', 'thrust');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        app(ResourceManager::class)->boot();
    }

    public function register() {
        app()->singleton(ResourceManager::class , function(){
            return new ResourceManager();
        });
    }

}