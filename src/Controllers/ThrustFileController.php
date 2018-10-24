<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Routing\Controller;

class ThrustFileController extends Controller
{

    public function edit($resourceName, $id, $field)
    {
        $resource = Thrust::make($resourceName);
        return view('thrust::editFile',[
            "resourceName"  => $resourceName,
            "object"        => $resource->find($id),
            "fileField"    => $resource->fieldFor($field),
        ]);
    }

    public function store($resourceName, $id, $field)
    {
        if (! request()->hasFile('file')) return back()->withMessage('noFile');

        $resource = Thrust::make($resourceName);
        $fileField = $resource->fieldFor($field);
        $object     = $resource->find($id);
        $fileField->store($object, request()->file('file'));
        return back()->withMessage('updated');
    }

    public function delete($resourceName, $id, $field)
    {
        $resource = Thrust::make($resourceName);
        $imageField = $resource->fieldFor($field);
        $object     = $resource->find($id);
        $imageField->delete($object, true);
        return back()->withMessage('deleted');
    }

}