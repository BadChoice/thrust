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
    abstract public function apply(Request $request, $query, $value);

    abstract public function display($filtersApplied);

    public function class()
    {
        return get_class($this);
    }

    public function title()
    {
        return niceTitle(collect(explode('\\', get_class($this)))->last());
    }

    public function filterValue($filtersApplied)
    {
        if (! $filtersApplied->keys()->contains($this->class())) {
            return null;
        }
        return $filtersApplied[$this->class()];
    }
}
