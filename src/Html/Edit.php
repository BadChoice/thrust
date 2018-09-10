<?php

namespace BadChoice\Thrust\Html;

use BadChoice\Thrust\Fields\Hidden;
use BadChoice\Thrust\Resource;

class Edit
{

    protected $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function getEditFields()
    {
        $fields = collect($this->resource->fields())->where('showInEdit', true);
        if ($this->resource::$sortable) {
            $fields->prepend(Hidden::make($this->resource::$sortField));
        }
        return $fields;
    }

    public function getVisiblityJson()
    {
        return $this->resource->panels()->filter(function($panel){
            return $panel->hideWhenField != null;
        })->mapWithKeys(function($panel){
            return [$panel->title => [
                "field" => $panel->hideWhenField,
                "value" => $panel->hideWhenValue]
            ];
        });
    }

    public function show($id)
    {
        $object = is_numeric($id) ? $this->resource->find($id) : $id;
        return view('thrust::edit', [
            'nameField'     => $this->resource->nameField,
            'resourceName'  => $this->resource->name(),
            'fields'        => $this->getEditFields(),
            'object'        => $object,
            'visibility'    => $this->getVisiblityJson(),
        ])->render();
    }

}