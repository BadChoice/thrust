<?php

namespace BadChoice\Thrust\Controllers;


use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Routing\Controller;

class ThrustBelongsToManyController extends Controller
{
    public function index($resourceName, $id, $relationship){
        $resource           = Thrust::make($resourceName);
        $object             = $resource->find($id);
        $belongsToManyField = $resource->fieldFor($relationship);
        return view('thrust::belongsToManyIndex',[
            "resourceName"            => $resourceName,
            "object"                  => $object,
            "title"                   => $object->{$resource->nameField},
            "children"                => $object->{$relationship},
            "belongsToManyField"      => $belongsToManyField,
            "relationshipDisplayName" => $belongsToManyField->relationDisplayField,
            "searchable"              => $belongsToManyField->searchable,
            "ajaxSearch"              => $belongsToManyField->ajaxSearch,
            "allowDuplicates"         => $belongsToManyField->allowDuplicates ? "1" : "0",
            "sortable"                => $belongsToManyField->sortable,
        ]);
    }

    public function store($resourceName, $id, $relationship)
    {
        $resource = Thrust::make($resourceName);
        $object = $resource->find($id);
        $object->{$relationship}()->attach(request('id'), request()->except(['id', '_token']));
        return back()->withMessage('added');
    }

    public function delete($resourceName, $id, $relationship, $detachId)
    {
        $resource = Thrust::make($resourceName);
        $object = $resource->find($id);
        //$object->{$relationship}()->detach($detachId);
        //dd($object->{$relationship}()->wherePivot('id', $detachId)->first());
        $relationObject = $object->{$relationship}()->wherePivot('id', $detachId)->first();
        $relationObject->pivot->delete();
        return back()->withMessage('deleted');
    }

    public function search($resourceName, $id, $relationship, $searchText)
    {
        request()->merge(["search" => $searchText]);
        $resource           = Thrust::make($resourceName);
        $object             = $resource->find($id);
        $belongsToManyField = $resource->fieldFor($relationship);
        $children           = $belongsToManyField->search($object, $searchText)->get();
        return view('thrust::belongsToManyTable',[
            'resourceName'       => $resourceName,
            'object'             => $object,
            'belongsToManyField' => $belongsToManyField,
            "relationshipDisplayName" => $belongsToManyField->relationDisplayField,
            'children'           => $children,
        ]);

    }
}