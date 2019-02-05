<?php

namespace BadChoice\Thrust\Fields;

class Currency extends Decimal
{
    public function displayInIndex($object)
    {
        if ($this->displayInIndexCallback) {
            return number_format(call_user_func($this->displayInIndexCallback, $object), 2) . ' €';
        }
        return number_format($this->getValue($object), 2) . ' €';
    }
}
