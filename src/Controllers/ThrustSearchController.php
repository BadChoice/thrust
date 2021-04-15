<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\Html\Index;
use Illuminate\Routing\Controller;

class ThrustSearchController extends Controller
{
    public function index($resourceName, $searchText)
    {
        request()->merge(['search' => $searchText]);
        $resource = Thrust::make($resourceName);
        return (new Index($resource::$searchResource ? new $resource::$searchResource: $resource))->show();
    }
}
