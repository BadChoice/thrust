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
        if ($resource::$searchResource){        
            $resource = Thrust::make($resource::$searchResource);
        }
        return (new Index($resource))->show();
    }
}
