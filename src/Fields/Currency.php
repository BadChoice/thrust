<?php

namespace BadChoice\Thrust\Fields;

class Currency extends Decimal {

    public function displayInIndex($object){
        return number_format($this->getValue($object), 2) . ' €';
    }


}
