<?php


namespace BadChoice\Thrust\Visibility;


class Show
{

    public $field;
    public $values;
    public $callback;

    public function showWhen($field, $values = true)
    {
        if (!is_array($values)) {$values = [$values]; }
        $this->field    = $field;
        $this->values    = $values;
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
        if (! $object)
            return false;
        return in_array($object->{$this->field}, $this->values);
    }
}