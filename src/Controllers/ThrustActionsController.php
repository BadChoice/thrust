<?php

namespace BadChoice\Thrust\Controllers;


use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustActionsController extends Controller
{
    public function toggle($resourceName, $id, $field)
    {
        $resource  = app(ResourceManager::class)->make($resourceName);
        $object = $resource->find($id);
        $object->update([$field => !$object->{$field}]);
        return back();
    }

    public function perform($resourceName)
    {
        $actionClass        = request('action');
        $action             = new $actionClass;
        $action->resource   = app(ResourceManager::class)->make($resourceName);
        $response           = $action->handle($action->resource->find(request('ids')));
        return response()->json(["ok" => true, "message" => $response ?? "done"]);
    }
}