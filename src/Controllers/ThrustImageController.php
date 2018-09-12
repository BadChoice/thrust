<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Html\Edit;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustImageController extends Controller
{

    public function edit($resourceName, $id, $field)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        return view('thrust::editImage',[
            "resourceName"  => $resourceName,
            "object"        => $resource->find($id),
            "imageField"    => $resource->fieldFor($field),
        ]);
    }

    public function store($resourceName, $id, $field)
    {
        if (! request()->hasFile('image')) return back()->withMessage('noPhoto');

        $resource = app(ResourceManager::class)->make($resourceName);
        $imageField = $resource->fieldFor($field);
        $object     = $resource->find($id);
        $imageField->store($object, request()->file('image'));
        return back()->withMessage('updated');
    }

    public function delete($resourceName, $id, $field)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        $imageField = $resource->fieldFor($field);
        $object     = $resource->find($id);
        $imageField->delete($object, true);
        return back()->withMessage('deleted');
    }

}