<?php

namespace BadChoice\Thrust\Controllers;

use Illuminate\Routing\Controller;
use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\ChildResource;
use BadChoice\Thrust\Html\Index;

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
            'searchUrl'               => "/thrust/{$hasManyField->resourceName}/hasMany/{$id}/search/"
//            "object"                  => $object,
//            "title"                   => $object->{$resource->nameField},
//            "children"                => $object->{$relationship},
//            "belongsToManyField"      => $hasManyField,
        ]);
    }

    public function search($resourceName, $id, $searchText)
    {
        request()->merge(['search' => $searchText]);
        $resource = Thrust::make($resourceName)->parentId($id);
        return (new Index($resource::$searchResource ? new $resource::$searchResource: $resource))->show();
    }
}
