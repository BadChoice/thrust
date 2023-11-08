<?php


namespace BadChoice\Thrust\Visibility;


class Show
{

    public $field;
    public $values;
    public $callback;

    public function showWhen($field, $values = true)
    {
        $values = collect($values)->all();
        $this->field    = $field;
        $this->values    = $values;
    }

    public function showCallback($callback){
        $this->callback = $callback;
    }

    public function shouldShow($object, $conditionally = false)
    {
        if ($this->callback){
            return call_user_func($this->callback, $object);
        }
        if ($this->field == null) {
            return true;
        }
        if (! $object)
            return false;
        if ($conditionally) {
            return in_array($object->{$this->field}, $this->values);
        }
        return true;
    }
}