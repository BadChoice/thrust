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
        if(! method_exists($object, 'toggleActive')){
            $object->update([$field => !$object->{$field}]);
            return back();
        }
        try {
            $object->toggleActive();
            return back();
        }catch(\Exception $e){
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
    }

    public function create($resourceName)
    {
        $action = $this->findActionForResource($resourceName, request('action'));

        if (! $action) {
            abort(404);
        }
        
        $action->setSelectedTargets(collect(explode(',', request('ids'))));

        if(request('search')) {
            $resourceName = Thrust::make($resourceName)::$searchResource ?? $resourceName;
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

        try {
            $response   = $action->handle(collect($action->resource->find($ids)));
        } catch (\Exception $e) {
            return request()->ajax() ?
                response()->json(['ok' => false, 'message' => $e->getMessage(), 'shouldReload' => false, 'responseAsPopup' => false]) :
                back()->withErrors(['msg' => $e->getMessage()]);
        }

        if (request()->ajax()) {
            return response()->json(['ok' => true, 'message' => $response ?? 'done', 'shouldReload' => $action->shouldReload, 'responseAsPopup' => $action->responseAsPopup]);
        }

        return back()->withMessage($response);
    }

    public function index($resourceName)
    {
        $resource = Thrust::make($resourceName);

        return view('thrust::components.actionsIndex', [
            'actions' => collect($resource->searchActions(request('search'))),
            'resourceName' => $resource->name(),
        ]);
    }

    private function findActionForResource($resourceName, $actionClass)
    {
        $resource   = Thrust::make($resourceName);
        $action     =  collect($resource->searchActions(request('search')))->first(function ($action) use ($actionClass) {
            return $action instanceof $actionClass;
        });

        $action->resource = request('search') && $resource::$searchResource
            ? Thrust::make($resource::$searchResource)
            : $resource;
            
        return $action;
    }
}
