<?php

namespace BadChoice\Thrust\Fields;

class Integer extends Decimal
{
    protected function getFieldAttributes()
    {
        return $this->attributes . ' step=1';
    }
}
