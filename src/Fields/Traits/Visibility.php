<?php

namespace BadChoice\Thrust\Fields\Traits;

trait Visibility
{
    public $hideWhenField;
    public $hideWhenValue;

    public function hideWhen($field, $value = true)
    {
        $this->hideWhenField = $field;
        $this->hideWhenValue = $value;
        return $this;
    }

    public function shouldHide($object)
    {
        if ($this->hideWhenField == null) {
            return false;
        }
        return $object->{$this->hideWhenField} === $this->hideWhenValue;
    }
}
