<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\Html\Edit;
use BadChoice\Thrust\ResourceGate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;

class ThrustController extends Controller
{

    use AuthorizesRequests;

    public function index($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'index');

        if ($resource::$singleResource){
            return $this->singleResourceIndex($resourceName, $resource);
        }

        return view('thrust::index',[
            'resourceName' => $resourceName,
            'resource' => $resource,
            'searchable' => count($resource::$search) > 0,
            'description' => $resource->getDescription(),
        ]);
    }

    public function create($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'create');
        $object = $resource->makeNew();
        return (new Edit($resource))->show($object);
    }

    public function edit($resourceName, $id)
    {
        $resource = Thrust::make($resourceName);
        $object = $resource->find($id);
        app(ResourceGate::class)->check($resource, 'update', $object);
        return (new Edit($resource))->show($object);
    }

    public function editInline($resourceName, $id)
    {
        $resource = Thrust::make($resourceName);
        return (new Edit($resource))->showInline($id);
    }

    public function store($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'create');
        request()->validate($resource->getValidationRules(null));
        $object = $resource::$model::create(request()->all());
        return back()->withMessage(__('thrust::messages.created'));
    }

    public function update($resourceName, $id)
    {
        $resource = Thrust::make($resourceName);
        if (! request()->has('inline')){
            request()->validate($resource->getValidationRules($id));
        }

        $resource->update($id, request()->except('inline'));
        return back()->withMessage(__('thrust::messages.updated'));
    }

    public function delete($resourceName, $id)
    {
        Thrust::make($resourceName)
                                   ->delete($id);
        return back()->withMessage(__('thrust::messages.deleted'));
    }

    private function singleResourceIndex($resourceName, $resource)
    {
        return view('thrust::singleResourceIndex',[
            "resourceName"  => $resourceName,
            "resource"      => $resource,
            "object"        => $resource->first()
        ]);
    }
}