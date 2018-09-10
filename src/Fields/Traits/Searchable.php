<?php

namespace BadChoice\Thrust\Fields\Traits;


trait Searchable
{
    public $searchable = false;
    public $ajaxSearch = false;

    public function searchable($searchable = true, $usingAjax = false)
    {
        $this->searchable = $searchable;
        $this->ajaxSearch = $usingAjax;
        return $this;
    }

}