<?php

namespace BadChoice\Thrust\Fields\Traits;

trait Visibility
{
    public $hideWhenField;
    public $hideWhenValue;
    public $showWhenField;
    public $showWhenValue;
    public $invert;

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

    public function shouldHide($object)
    {
        if ($this->hideWhenField == null) {
            return false;
        }
        return $object->{$this->hideWhenField} !== $this->hideWhenValue;
    }

    public function shouldShow($object)
    {
        if ($this->showWhenField == null) {
            return true;
        }
        return $object->{$this->showWhenField} === $this->showWhenValue;
    }
}
