<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Routing\Controller;

class ThrustSortController extends Controller
{
    public function updateOrder($resourceName)
    {
        $resource  = Thrust::make($resourceName);
        $idsSorted = request('sort');
        $page = request('page') ?? 1;
        $objects   = $resource->find($idsSorted);
        $idsSorted = array_flip($idsSorted);
        $objects->each(function ($object) use ($idsSorted, $page) {
            $object->update(['order' => $idsSorted[$object->id] * $page]);
        });
        return response()->json('OK', 200);
    }
}
