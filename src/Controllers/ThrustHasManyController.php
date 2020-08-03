<?php

namespace BadChoice\Thrust\Controllers;

use Illuminate\Routing\Controller;
use BadChoice\Thrust\Facades\Thrust;

class ThrustHasManyController extends Controller
{
    public function index($resourceName, $id, $relationship)
    {
        $resource           = Thrust::make($resourceName);
        $object             = $resource->find($id);
        $hasManyField       = $resource->fieldFor($relationship);
        $childResource      = Thrust::make($hasManyField->resourceName)->parentId($id);

        return view('thrust::index', [
            'resourceName'            => $hasManyField->resourceName,
            'searchable'              => count($resource::$search) > 0,
            'resource'                => $childResource,
            'parent_id'               => $id,
            'minSearchChars'          => $childResource::$minSearchChars,
//            "object"                  => $object,
//            "title"                   => $object->{$resource->nameField},
//            "children"                => $object->{$relationship},
//            "belongsToManyField"      => $hasManyField,
        ]);
    }
}
