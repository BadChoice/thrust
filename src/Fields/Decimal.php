<?php

namespace BadChoice\Thrust\Fields;

class Decimal extends Text {

    protected function getFieldType()
    {
        return 'number';
    }

    protected function getFieldAttributes(){
        return 'step=any';
    }


}
