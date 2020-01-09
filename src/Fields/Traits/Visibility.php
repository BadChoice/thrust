<?php

namespace BadChoice\Thrust\Fields\Traits;

trait Visibility
{
    public $hideWhenField;
    public $hideWhenValue;
    public $showWhenField;
    public $showWhenValue;
    public $showCallback;
    public $hideEditWhenField;
    public $hideEditWhenValue;
    public $showEditWhenField;
    public $showEditWhenValue;
    public $showEditCallback;

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

    public function hideEditWhen($field, $value = true)
    {
        $this->hideEditWhenField    = $field;
        $this->hideEditWhenValue    = $value;
        return $this;
    }

    public function showEditWhen($field, $value = true)
    {
        $this->showEditWhenField    = $field;
        $this->showEditWhenValue    = $value;
        return $this;
    }

    public function showCallback($callback){
        $this->showCallback = $callback;
        return $this;
    }

    public function showEditCallback($callback){
        $this->showEditCallback = $callback;
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

    public function shouldHideInEdit($object)
    {
        if ($this->hideEditWhenField == null || $this->showEditCallback) {
            return false;
        }
        return $object->{$this->hideEditWhenField} === $this->hideEditWhenValue;
    }

    public function shouldShowInEdit($object)
    {
        if ($this->showEditCallback){
            return call_user_func($this->showEditCallback, $object);
        }
        if ($this->showEditWhenField == null) {
            return true;
        }
        return $object->{$this->showEditWhenField} === $this->showEditWhenValue;
    }
}
