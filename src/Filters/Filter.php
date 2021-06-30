<?php

namespace BadChoice\Thrust\Filters;

use BadChoice\Thrust\Helpers\Iconable;
use BadChoice\Thrust\Helpers\Titleable;
use Illuminate\Http\Request;

abstract class Filter
{
    use Titleable;
    use Iconable;

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

    public function filterValue($filtersApplied)
    {
        if (! $filtersApplied->keys()->contains($this->class())) {
            return null;
        }
        return $filtersApplied[$this->class()];
    }
}
