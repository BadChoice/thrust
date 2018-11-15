<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Routing\Controller;

class ThrustFileController extends Controller
{
    protected $blade        = 'editFile';
    protected $inputName    = 'file';

    public function edit($resourceName, $id, $field)
    {
        $resource = Thrust::make($resourceName);
        return view('thrust::'.$this->blade, [
            'resourceName'  => $resourceName,
            'object'        => $resource->find($id),
            'fileField'     => $resource->fieldFor($field),
        ]);
    }

    public function store($resourceName, $id, $field)
    {
        if (! request()->hasFile($this->inputName)) {
            return back()->withMessage('noFile');
        }

        $resource   = Thrust::make($resourceName);
        $fileField  = $resource->fieldFor($field);
        $object     = $resource->find($id);
        $fileField->store($object, request()->file($this->inputName));
        return back()->withMessage('updated');
    }

    public function delete($resourceName, $id, $field)
    {
        $resource   = Thrust::make($resourceName);
        $imageField = $resource->fieldFor($field);
        $object     = $resource->find($id);
        $imageField->delete($object, true);
        return back()->withMessage('deleted');
    }
}
