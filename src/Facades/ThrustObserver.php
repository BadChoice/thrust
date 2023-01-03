<?php

namespace BadChoice\Thrust\Facades;

use BadChoice\Thrust\ThrustObserver as Observer;
use Illuminate\Support\Facades\Facade;

class ThrustObserver extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Observer::class;
    }
}
