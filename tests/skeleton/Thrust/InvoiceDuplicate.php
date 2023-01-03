<?php

namespace App\Thrust;

use BadChoice\Thrust\Resource;

class InvoiceDuplicate extends Resource
{
    public static $model = \App\Models\Invoice::class;

    public function fields()
    {
        return [];
    }
}
