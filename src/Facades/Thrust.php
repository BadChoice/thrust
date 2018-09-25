<?php

namespace BadChoice\Thrust\Facades;


use Illuminate\Support\Facades\Facade;

class Thrust extends Facade
{
    protected static function getFacadeAccessor() { return 'thrust'; }
}