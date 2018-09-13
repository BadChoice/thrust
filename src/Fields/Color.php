<?php

namespace BadChoice\Thrust\Fields;

class Color extends Text{

    public $rowClass = 'action';

    protected function getFieldType(){
        return 'color';
    }

}
