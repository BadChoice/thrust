<?php

namespace BadChoice\Thrust\Html;

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
        return collect($this->resource->fields())->where('showInEdit', true);
    }

    public function show($id)
    {
        $object = is_numeric($id) ? $this->resource->find($id) : $id;
        return view('thrust::edit', [
            'nameField'     => $this->resource->nameField,
            'resourceName'  => $this->resource->name(),
            'fields'        => $this->getEditFields(),
            'object'        => $object
        ])->render();
    }

}