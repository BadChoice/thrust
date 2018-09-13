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

        return "TODO!";
        /*return view('thrust::hasManyIndex',[
            "resourceName"            => $resourceName,
            "object"                  => $object,
            "title"                   => $object->{$resource->nameField},
            "children"                => $object->{$relationship},
            "belongsToManyField"      => $hasManyField,
        ]);*/
    }


}