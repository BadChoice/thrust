<?php

namespace BadChoice\Thrust\Controllers;


use BadChoice\Thrust\Html\Index;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustBelongsToManyController extends Controller
{
    public function index($resourceName, $id, $relationship){
        $resource           = app(ResourceManager::class)->make($resourceName);
        $object             = $resource->find($id);
        $belongsToManyField = $resource->fieldsFlattened()->where('field', $relationship)->first();
        return view('thrust::belongsToManyIndex',[
            "resourceName"            => $resourceName,
            "object"                  => $object,
            "title"                   => $object->{$resource->nameField},
            "children"                => $object->{$relationship},
            "belongsToManyField"      => $belongsToManyField,
            "relationshipDisplayName" => $belongsToManyField->relationDisplayField,
        ]);
    }

    public function store($resourceName, $id, $relationship)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        $object = $resource->find($id);
        $object->{$relationship}()->attach(request('id'));
        return back()->withMessage('added');
    }
}