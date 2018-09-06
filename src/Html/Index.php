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
            'fields' => $this->resource->getFields(),
            'rows' => $this->resource->getRows()
        ]);
    }


}