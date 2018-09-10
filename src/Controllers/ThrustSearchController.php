<?php

namespace BadChoice\Thrust\Controllers;


use BadChoice\Thrust\Html\Index;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustSearchController extends Controller
{
    public function index($resourceName, $searchText)
    {
        request()->merge(["search" => $searchText]);
        $resource = app(ResourceManager::class)->make($resourceName);
        return (new Index($resource))->show();
    }
}