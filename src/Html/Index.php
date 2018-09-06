<?php

namespace BadChoice\Thrust\Html;

use BadChoice\Thrust\Resource;

class Index
{
    protected $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function show()
    {
        return view('thrust::index', [
            'resource'  => $this->resource,
            'fields'    => $this->resource->fields(),
            'rows'      => $this->resource->rows()
        ]);
    }


}