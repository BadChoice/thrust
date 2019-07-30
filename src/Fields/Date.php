<?php

namespace BadChoice\Thrust\Fields;

class Date extends Text
{
    protected $timeAgo;
    protected $format;

    protected function getFieldType()
    {
        return 'date';
    }

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    public function displayInIndex($object){
        $value = data_get($object, $this->field);
        if ($value && $this->timeAgo)
            return $object->{$this->field}->diffForHumans();
        if ($value && $this->format){
            return $object->{$this->field}->format($this->format);
        }
        return parent::displayInIndex($object);
    }

    public function showInTimeAgo($timeAgo = true)
    {
        $this->timeAgo = $timeAgo;
        return $this;
    }
}
