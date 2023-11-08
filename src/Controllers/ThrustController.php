<?php

namespace BadChoice\Thrust\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use BadChoice\Thrust\ResourceGate;
use BadChoice\Thrust\Html\Edit;
use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Support\Facades\DB;

class ThrustController extends Controller
{
    use AuthorizesRequests;

    public function index($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'index');

        if ($resource::$singleResource) {
            return $this->singleResourceIndex($resourceName, $resource);
        }

        return view('thrust::index', [
            'resourceName' => $resourceName,
            'resource'     => $resource,
            'searchable'   => count($resource::$search) > 0,
            'description'  => $resource->getDescription(),
        ]);
    }

    public function create($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'create');
        $object = $resource->makeNew();
        return (new Edit($resource))->show($object);
    }

    public function createMultiple($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'create');
        $object = $resource->makeNew();
        return (new Edit($resource))->show($object, false, true);
    }

    public function edit($resourceName, $id)
    {
        $resource = Thrust::make($resourceName);
        $object   = $resource->find($id);
        app(ResourceGate::class)->check($resource, 'update', $object);
        return (new Edit($resource))->show($object);
    }

    public function editInline($resourceName, $id)
    {
        $resource = Thrust::make($resourceName);
        return (new Edit($resource))->showInline($id);
    }

    public function store($resourceName)
    {
        $resource = Thrust::make($resourceName);
        request()->validate($resource->getValidationRules(null));
        try {
            $result = $resource->create(request()->all());
        } catch (\Exception $e) {
            if (request()->ajax()) { return response()->json(["error" => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);}
            return back()->withErrors(['message' => $e->getMessage()]);
        }
        if (request()->ajax()) { return response()->json($result);}
        return back()->withMessage(__('thrust::messages.created'));
    }

    public function storeMultiple($resourceName)
    {
        $resource = Thrust::make($resourceName);
        request()->validate($resource->getValidationRules(null, true));
        $amount   = request()->input('amount'); // amount to create
        $request  = request()->except('amount');

        DB::beginTransaction();
        for ($i = 0; $i < $amount; ++$i) {
            $request = array_merge($request, $resource->generateMultipleFields());

            try {
                $resource->create($request);
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['message' => $e->getMessage()]);
            }
        }

        DB::commit();
        return back()->withMessage(__('thrust::messages.created'));
    }

    public function update($resourceName, $id)
    {
        $resource = Thrust::make($resourceName);
        if (! request()->has('inline')) {
            request()->validate($resource->getValidationRules($id));
        }

        try {
            $resource->update($id, request()->except('inline'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
        return back()->withMessage(__('thrust::messages.updated'));
    }

    public function delete($resourceName, $id)
    {
        try {
            Thrust::make($resourceName)->delete($id);
        } catch (\Exception $e) {
            return back()->withErrors(['delete' => $e->getMessage()]);
        }
        return back()->withMessage(__('thrust::messages.deleted'));
    }

    private function singleResourceIndex($resourceName, $resource)
    {
        return view('thrust::singleResourceIndex', [
            'resourceName'  => $resourceName,
            'resource'      => $resource,
            'object'        => $resource->first()
        ]);
    }
}
