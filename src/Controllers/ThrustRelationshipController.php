<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Routing\Controller;

class ThrustRelationshipController extends Controller
{
    public function search($resourceName, $id, $relationship)
    {
        $resource       = Thrust::make($resourceName);
        $relationField  = $resource->fieldFor($relationship);
        $object         = $resource->find($id) ?? new $resource::$model;
        $query          = $relationField->searchRelatedQuery($object, request('search'), request('allowDuplicates', true));
        $results        = $query->get();

        if (request('allowNull', false)){
           return $results->prepend(["id" => "", "name" => "--"]);
        }

        return $results;
    }

}