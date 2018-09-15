<?php

namespace BadChoice\Thrust\Controllers;


use BadChoice\Thrust\Html\Index;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustHasManyController extends Controller
{
    public function index($resourceName, $id, $relationship){
        $resource           = app(ResourceManager::class)->make($resourceName);
        $object             = $resource->find($id);
        $hasManyField       = $resource->fieldFor($relationship);
        $childResource      = app(ResourceManager::class)->make($hasManyField->resourceName)->parentId($id);

        return view('thrust::mainIndex',[
            "resourceName"            => $hasManyField->resourceName,
            'searchable'              => count($resource::$search) > 0,
            "resource"                => $childResource,
            "parent_id"               => $id,
//            "object"                  => $object,
//            "title"                   => $object->{$resource->nameField},
//            "children"                => $object->{$relationship},
//            "belongsToManyField"      => $hasManyField,
        ]);
    }


}