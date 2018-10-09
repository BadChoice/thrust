<?php

namespace BadChoice\Thrust\Filters;

use Illuminate\Http\Request;

abstract class Filter
{

    public function class()
    {
        return get_class($this);
    }

    public function title()
    {
        return collect(explode("\\", get_class($this)))->last();
    }

    /**
     * @param Request $request
     * @param $query
     * @param $value
     * @return QueryBuilder with the filter applied
     */
    public abstract function apply(Request $request, $query, $value);

    /**
     * @return array where the key is the human readable part and the value is the value that will be sent to the apply
     */
    public abstract function options();
}