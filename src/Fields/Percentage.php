<?php

namespace BadChoice\Thrust\Fields;

class Percentage extends Decimal
{
    public function displayInIndex($object)
    {
        return $this->getValue($object) . ' %';
    }
}
