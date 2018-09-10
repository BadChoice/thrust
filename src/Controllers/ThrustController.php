<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Html\Edit;
use BadChoice\Thrust\Html\Index;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustController extends Controller
{

    public function index($resourceName)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        return view('thrust::mainIndex',[
            'resourceName' => $resourceName,
            'resource' => $resource,
            'searchable' => count($resource::$search) > 0
        ]);
    }

    public function create($resourceName)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        $object = $resource->makeNew();
        return (new Edit($resource))->show($object);
    }

    public function edit($resourceName, $id)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        return (new Edit($resource))->show($id);
    }

    public function store($resourceName)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        request()->validate($resource->getValidationRules(null));
        $object = $resource::$model::create(request()->all());
        //dd($object);
        return back()->withMessage(__('created'));
    }

    public function update($resourceName, $id)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        request()->validate($resource->getValidationRules($id));

        $resource->update($id, request()->all());
        return back()->withMessage(__('updated'));
    }

    public function delete($resourceName, $id)
    {
        app(ResourceManager::class)->make($resourceName)
                                   ->delete($id);
        return back()->withMessage(__('deleted'));
    }

    public function search($resourceName, $searchText)
    {
        request()->merge(["search" => $searchText]);
        $resource = app(ResourceManager::class)->make($resourceName);
        return (new Index($resource))->show();
    }
}