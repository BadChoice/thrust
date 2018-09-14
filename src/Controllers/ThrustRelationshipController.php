<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustRelationshipController extends Controller
{
    public function search($resourceName, $id, $relationship)
    {
        $resource       = app(ResourceManager::class)->make($resourceName);
        $relationField = $resource->fieldFor($relationship);
        $object         = $resource->find($id);
        $query          = $relationField->searchRelatedQuery($object, request('search'), request('allowDuplicates', true));
        $results        = $query->get();

        if (request('allowNull', false)){
           return $results->prepend(["id" => "", "name" => "--"]);
        }

        return $results;
    }

}