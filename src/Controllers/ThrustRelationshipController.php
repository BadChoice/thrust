<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustRelationshipController extends Controller
{
    public function search($resourceName, $id, $relationship)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        $searchText = request('search');
        $results =  $resource->find($id)->{$relationship}()->getRelated()->query()
                        ->where('name','like','%'.$searchText.'%')
                        ->select('id','name')->limit(10)->get();

        if (request('allowNull')){
           return $results->prepend(["id" => "", "name" => "--"]);
        }
        return $results;
    }

}