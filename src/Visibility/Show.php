<?php


namespace BadChoice\Thrust\Visibility;


class Show
{

    public $field;
    public $value;
    public $callback;

    public function showWhen($field, $value = true)
    {
        $this->field    = $field;
        $this->value    = $value;
    }

    public function showCallback($callback){
        $this->callback = $callback;
    }

    public function shouldShow($object)
    {
        if ($this->callback){
            return call_user_func($this->callback, $object);
        }
        if ($this->field == null) {
            return true;
        }
        return $object->{$this->field} === $this->value;
    }
}