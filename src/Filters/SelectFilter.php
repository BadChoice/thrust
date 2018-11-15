<?php

namespace BadChoice\Thrust\Filters;

abstract class SelectFilter extends Filter
{
    /**
     * @return array where the key is the human readable part and the value is the value that will be sent to the apply
     */
    abstract public function options();

    public function display($filtersApplied)
    {
        return view('thrust::filters.select', [
            'filter' => $this,
            'value'  => $this->filterValue($filtersApplied),
        ])->render();
    }
}
