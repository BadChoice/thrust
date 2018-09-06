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

    public function show($id)
    {
        return view('thrust::edit', [
            'fields' => $this->resource->fields(),
            'object' => $this->resource->find($id)
        ]);
    }

}