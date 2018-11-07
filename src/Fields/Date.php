<?php

namespace BadChoice\Thrust\Fields;

class Date extends Text {

    protected $timeAgo;

    protected function getFieldType()
    {
        return 'date';
    }

    public function displayInIndex($object){
        if ($this->timeAgo)
            return $object->{$this->field}->diffForHumans();

        return parent::displayInIndex($object);
    }

    public function showInTimeAgo($timeAgo = true)
    {
        $this->timeAgo = $timeAgo;
        return $this;
    }

}
