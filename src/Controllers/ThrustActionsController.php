<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Routing\Controller;

class ThrustActionsController extends Controller
{
    public function toggle($resourceName, $id, $field)
    {
        $resource  = Thrust::make($resourceName);
        $object    = $resource->find($id);
        $object->update([$field => ! $object->{$field}]);
        return back();
    }

    public function create($resourceName)
    {
        $action      = $this->findActionForResource($resourceName, request('action'));
        if (! $action) {
            abort(404);
        }

        return view('thrust::actions.create', [
            'action'        => $action,
            'resourceName'  => $resourceName,
            'ids'           => request('ids')
        ]);
    }

    public function perform($resourceName)
    {
        $action     = $this->findActionForResource($resourceName, request('action'));
        $ids        = is_string(request('ids')) ? explode(',', request('ids')) : request('ids');
        $response   = $action->handle($action->resource->find($ids));

        if (request()->ajax()) {
            return response()->json(['ok' => true, 'message' => $response ?? 'done']);
        }

        return back()->withMessage($response);
    }

    private function findActionForResource($resourceName, $actionClass)
    {
        $resource   = Thrust::make($resourceName);
        $action     =  collect($resource->actions())->first(function ($action) use ($actionClass) {
            return $action instanceof $actionClass;
        });
        $action->resource = $resource;
        return $action;
    }
}
