<?php

namespace BadChoice\Thrust\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    /**
     * @param Request $request
     * @param $query
     * @param $value
     * @return QueryBuilder with the filter applied
     */
    public abstract function apply(Request $request, $query, $value);

    public abstract function display($filtersApplied);

    public function class()
    {
        return get_class($this);
    }

    public function title()
    {
        return collect(explode("\\", get_class($this)))->last();
    }

    public function filterValue($filtersApplied)
    {
        if (! $filtersApplied->keys()->contains($this->class())){
            return null;
        }
        return $filtersApplied[$this->class()];
    }

}