<?php

namespace BadChoice\Thrust\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    public $title = null;

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
        $title = $this->title ?? niceTitle(collect(explode('\\', get_class($this)))->last());
        if (strpos(__('admin.'.$title), 'admin.') === false) {
            $title = __('admin.'.$title);
        }
        return $title;
    }

    public function filterValue($filtersApplied)
    {
        if (! $filtersApplied->keys()->contains($this->class())) {
            return null;
        }
        return $filtersApplied[$this->class()];
    }
}
