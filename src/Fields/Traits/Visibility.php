<?php

namespace BadChoice\Thrust\Fields\Traits;

trait Visibility
{
    public $hideWhenField;
    public $hideWhenValue;
    public $showWhenField;
    public $showWhenValue;
    public $showCallback;

    public function hideWhen($field, $value = true)
    {
        $this->hideWhenField    = $field;
        $this->hideWhenValue    = $value;
        return $this;
    }

    public function showWhen($field, $value = true)
    {
        $this->showWhenField    = $field;
        $this->showWhenValue    = $value;
        return $this;
    }

    public function showCallback($callback){
        $this->showCallback = $callback;
        return $this;
    }

    public function shouldHide($object)
    {
        if ($this->hideWhenField == null || $this->showCallback) {
            return false;
        }
        return $object->{$this->hideWhenField} === $this->hideWhenValue;
    }

    public function shouldShow($object)
    {
        if ($this->showCallback){
            return call_user_func($this->showCallback, $object);
        }
        if ($this->showWhenField == null) {
            return true;
        }
        return $object->{$this->showWhenField} === $this->showWhenValue;
    }
}
