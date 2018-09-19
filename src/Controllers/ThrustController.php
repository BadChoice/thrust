<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Html\Edit;
use BadChoice\Thrust\ResourceManager;
use BadChoice\Thrust\ResourceGate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;

class ThrustController extends Controller
{

    use AuthorizesRequests;

    public function index($resourceName)
    {
        $resource = app(ResourceManager::class)->make(str_plural($resourceName));
        app(ResourceGate::class)->check($resource, 'index');

        if ($resource::$singleResource){
            return $this->singleResourceIndex($resourceName, $resource);
        }

        return view('thrust::mainIndex',[
            'resourceName' => $resourceName,
            'resource' => $resource,
            'searchable' => count($resource::$search) > 0
        ]);
    }

    public function create($resourceName)
    {
        $resource = $this->getAndAuthorizeResource($resourceName);
        app(ResourceGate::class)->check($resource, 'create');
        $object = $resource->makeNew();
        return (new Edit($resource))->show($object);
    }

    public function edit($resourceName, $id)
    {
        $resource = app(ResourceManager::class)->make(str_plural($resourceName));
        $object = $resource->find($id);
        app(ResourceGate::class)->check($resource, 'update', $object);
        return (new Edit($resource))->show($object);
    }

    public function editInline($resourceName, $id)
    {
        $resource = app(ResourceManager::class)->make(str_plural($resourceName));
        return (new Edit($resource))->showInline($id);
    }

    public function store($resourceName)
    {
        $resource = app(ResourceManager::class)->make(str_plural($resourceName));
        app(ResourceGate::class)->check($resource, 'create');
        request()->validate($resource->getValidationRules(null));
        $object = $resource::$model::create(request()->all());
        return back()->withMessage(__('created'));
    }

    public function update($resourceName, $id)
    {
        $resource = app(ResourceManager::class)->make(str_plural($resourceName));
        if (! request()->has('inline')){
            request()->validate($resource->getValidationRules($id));
        }

        $resource->update($id, request()->except('inline'));
        return back()->withMessage(__('updated'));
    }

    public function delete($resourceName, $id)
    {
        app(ResourceManager::class)->make(str_plural($resourceName))
                                   ->delete($id);
        return back()->withMessage(__('deleted'));
    }

    private function singleResourceIndex($resourceName, $resource)
    {
        return view('thrust::singleResourceIndex',[
            "resourceName"  => $resourceName,
            "resource"      => $resource,
            "object"        => $resource->first()
        ]);
    }

    private function getAndAuthorizeResource($resourceName){
        $resource = app(ResourceManager::class)->make(str_plural($resourceName));
        if ($resource::$gate){
            $this->authorize($resource::$gate);
        }
        return $resource;
    }
}