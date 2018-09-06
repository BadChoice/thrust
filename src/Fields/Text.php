<?php

namespace BadChoice\Thrust\Fields;

class Text extends Field{

    public function displayInIndex($object){
        return $object->{$this->field};
    }

}
