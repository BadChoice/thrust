<?php

namespace BadChoice\Thrust\Controllers;


use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustRelationshipController extends Controller
{
    public function search($resourceName, $id, $relationship)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        return $resource->find($id)->{$relationship}()->getRelated()->query()
            ->where('name','like','%'.request('text').'%')
            ->get(['id','name']);
    }

}