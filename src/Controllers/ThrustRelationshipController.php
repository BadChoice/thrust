<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustRelationshipController extends Controller
{
    public function search($resourceName, $id, $relationship)
    {
        $resource   = app(ResourceManager::class)->make($resourceName);
        $object     = $resource->find($id);
        $query      = $object->{$relationship}()->getRelated()->query()
                        ->where('name','like','%'.request('search').'%')
                        ->select('id','name')->limit(10);

        if (! request('allowDuplicates', true)){
            $query->whereNotIn('id', $object->{$relationship}->pluck('id')->toArray());
        }

        $results = $query->get();

        if (request('allowNull', false)){
           return $results->prepend(["id" => "", "name" => "--"]);
        }

        return $results;
    }

}