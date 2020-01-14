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
        switch ($where){
            case 'edit':
                $this->hideEdit->hideWhen($field, $value);
                break;
            case 'index':
                $this->hideIndex->hideWhen($field, $value);
                break;
            default:
                $this->hideEdit->hideWhen($field, $value);
                $this->hideIndex->hideWhen($field, $value);
                break;
        }
        return $this;
    }

    function showWhen($field, $value=true, $where=null){
        switch ($where){
            case 'edit':
                $this->showEdit->showWhen($field, $value);
                break;
            case 'index':
                $this->showIndex->showWhen($field, $value);
                break;
            default:
                $this->showEdit->showWhen($field, $value);
                $this->showIndex->showWhen($field, $value);
                break;
        }
        return $this;
    }

    function hideCallback($callback, $where=null){
        switch ($where){
            case 'edit':
                $this->hideEdit->hideCallback($callback);
                break;
            case 'index':
                $this->hideIndex->hideCallback($callback);
                break;
            default:
                $this->hideEdit->hideCallback($callback);
                $this->hideIndex->hideCallback($callback);
                break;
        }
        return $this;
    }

    function showCallback($callback, $where=null){
        switch ($where){
            case 'edit':
                $this->showEdit->showCallback($callback);
                break;
            case 'index':
                $this->showIndex->showCallback($callback);
                break;
            default:
                $this->showEdit->showCallback($callback);
                $this->showIndex->showCallback($callback);
                break;
        }
        return $this;
    }

    function shouldHide($object, $where=null){
        switch ($where){
            case 'edit':
                return $this->hideEdit->shouldHide($object);
            case 'index':
                return $this->hideIndex->shouldHide($object);
            default:
                return $this->hideEdit->shouldHide($object) && $this->hideIndex->shouldHide($object);
        }
    }

    function shouldShow($object, $where=null){
        switch ($where){
            case 'edit':
                return $this->showEdit->shouldShow($object);
            case 'index':
                return $this->showIndex->shouldShow($object);
            default:
                return $this->showEdit->shouldShow($object) && $this->showIndex->shouldShow($object);
        }
    }
}
