<?php

namespace BadChoice\Thrust\Controllers;

use Illuminate\Routing\Controller;
use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\ChildResource;

class ThrustHasManyController extends Controller
{
    public function index($resourceName, $id, $relationship)
    {
        $resource           = Thrust::make($resourceName);
        $object             = $resource->find($id);
        $hasManyField       = $resource->fieldFor($relationship);
        $childResource      = Thrust::make($hasManyField->resourceName)->parentId($id);

        $backHasManyURLParams = $resource instanceof ChildResource ? $resource->getParentHasManyUrlParams($object) : null;

        return view('thrust::index', [
            'resourceName'            => $hasManyField->resourceName,
            'searchable'              => count($resource::$search) > 0,
            'resource'                => $childResource,
            'parent_id'               => $id,
            'isChild'                 => $resource instanceof ChildResource && $backHasManyURLParams,
            'hasManyBackUrlParams'    => $backHasManyURLParams,
//            "object"                  => $object,
//            "title"                   => $object->{$resource->nameField},
//            "children"                => $object->{$relationship},
//            "belongsToManyField"      => $hasManyField,
        ]);
    }
}
