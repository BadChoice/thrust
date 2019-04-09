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
        $startAt = request('startAt') ?? 0;
        $objects   = $resource->find($idsSorted);
        $idsSorted = array_flip($idsSorted);
        $objects->each(function ($object) use ($idsSorted, $startAt) {
            $object->update(['order' => $idsSorted[$object->id] + $startAt]);
        });
        return response()->json('OK', 200);
    }
}
