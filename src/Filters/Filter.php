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
        $translationsPrefix = config('thrust.translationsPrefix');
        if (strpos(__($translationsPrefix.$title), $translationsPrefix) === false) {
            $title = __($translationsPrefix.$title);
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
