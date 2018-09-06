<?php

namespace BadChoice\Thrust\Fields;

class Percentage extends Field{

    public function displayInIndex($object){
        return $object->{$this->field} . '%';
    }

}
