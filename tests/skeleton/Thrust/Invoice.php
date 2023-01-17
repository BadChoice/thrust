<?php

namespace App\Thrust;

use BadChoice\Thrust\Resource;

class Invoice extends Resource
{
    public static $model = \App\Models\Invoice::class;
    protected $overlook = ['token'];

    public function fields()
    {
        return [];
    }
}
