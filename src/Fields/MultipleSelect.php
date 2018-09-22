<?php

namespace BadChoice\Thrust\Fields;

class MultipleSelect extends Select{

    protected $options = [];
    protected $allowNull = false;
    protected $searchable = false;


    public function displayInIndex($object)
    {
        return collect($this->getValue($object))->map(function($value){
            return $this->getOptions()[$value];
        })->implode(', ');
    }

    protected function getAttributes()
    {
        return "multiple";
    }


}
