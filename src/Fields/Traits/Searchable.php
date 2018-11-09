<?php

namespace BadChoice\Thrust\Fields\Traits;

trait Searchable
{
    public $searchable   = false;
    public $ajaxSearch   = false;
    public $searchFields = null;

    public function searchable($searchable = true, $usingAjax = false)
    {
        $this->searchable = $searchable;
        $this->ajaxSearch = $usingAjax;
        return $this;
    }

    public function ajaxSearch($searchFields = null)
    {
        $this->searchable   = true;
        $this->ajaxSearch   = true;
        $this->searchFields = $searchFields;
        return $this;
    }
}
