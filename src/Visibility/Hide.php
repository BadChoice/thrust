<?php


namespace BadChoice\Thrust\Visibility;


class Hide
{

    public $field;
    public $values;
    public $callback;

    public function hideWhen($field, $values = [true])
    {
        $values = collect($values)->all();
        $this->field    = $field;
        $this->values    = $values;
    }

    public function hideCallback($callback){
        $this->callback = $callback;
    }

    public function shouldHide($object, $conditionally = false)
    {
        if ($this->field == null || $this->callback) {
            return false;
        }
        if (! $object)
            return true;
        if ($conditionally){
            return in_array($object->{$this->field}, $this->values);
        }
        return false;

    }
}