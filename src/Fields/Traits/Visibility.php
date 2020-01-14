<?php

namespace BadChoice\Thrust\Fields\Traits;


trait Visibility
{
    public $visibilities = [];

    function __get($value){
        if(! isset($this->visibilities[$value])){
            $class = "BadChoice\Thrust\Visibility\\".ucfirst(substr($value, 0, 4));
            $this->visibilities[$value] = new $class();
	    }
        return $this->visibilities[$value];
    }

    function hideWhen($field, $value=true, $where=null){
        if ($where == null || $where == 'index')
            $this->hideIndex->hideWhen($field, $value);
        if ($where == null || $where == 'edit')
            $this->hideEdit->hideWhen($field, $value);

        return $this;
    }

    function showWhen($field, $value=true, $where=null){
        if ($where == null || $where == 'index')
            $this->showIndex->showWhen($field, $value);
        if ($where == null || $where == 'edit')
            $this->showEdit->showWhen($field, $value);

        return $this;
    }

    function hideCallback($callback, $where=null){
        if ($where == null || $where == 'index')
            $this->hideIndex->hideCallback($callback);
        if ($where == null || $where == 'edit')
            $this->hideEdit->hideCallback($callback);

        return $this;
    }

    function showCallback($callback, $where=null){
        if ($where == null || $where == 'index')
            $this->showIndex->showCallback($callback);
        if ($where == null || $where == 'edit')
            $this->showEdit->showCallback($callback);

        return $this;
    }

    function shouldHide($object, $where=null){
        if ($where == 'index')
            return $this->hideIndex->shouldHide($object);
        if ($where == null || $where == 'edit')
            return $this->hideEdit->shouldHide($object);

        return $this->hideEdit->shouldHide($object) && $this->hideIndex->shouldHide($object);
    }

    function shouldShow($object, $where=null){
        if ($where == 'index')
            return $this->showIndex->shouldShow($object);
        if ($where == null || $where == 'edit')
            return $this->showEdit->shouldShow($object);

        return $this->showEdit->shouldShow($object) && $this->showIndex->shouldShow($object);
    }
}
