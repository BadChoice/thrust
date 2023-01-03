<?php

namespace App\Thrust;

use BadChoice\Thrust\Resource;

class Employee extends Resource
{
    public static $model = \App\Models\Employee::class;
    public static $observes = false;

    public function fields()
    {
        return [];
    }
}
