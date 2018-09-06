<?php

namespace BadChoice\Thrust\Fields;

class Percentage extends Text {

    public function displayInIndex($object){
        return $this->getValue($object) . '%';
    }

    protected function getFieldType()
    {
        return 'number';
    }

    protected function getFieldAttributes(){
        return 'step=any';
    }


}
