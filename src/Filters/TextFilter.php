<?php

namespace BadChoice\Thrust\Filters;

abstract class TextFilter extends Filter
{
    public function display($filtersApplied){
        return view('thrust::filters.text',[
            "filter" => $this,
            "value" => $this->filterValue($filtersApplied),
        ]);
    }
}