<?php


namespace BadChoice\Thrust\Visibility;


class Hide
{

    public $field;
    public $value;
    public $callback;

    public function hideWhen($field, $value = true)
    {
        $this->field    = $field;
        $this->value    = $value;
    }

    public function hideCallback($callback){
        $this->callback = $callback;
    }

    public function shouldHide($object)
    {
        if ($this->field == null || $this->callback) {
            return false;
        }
        if (! $object)
            return true;
        return $object->{$this->field} === $this->value;
    }
}