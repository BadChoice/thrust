<?php

namespace BadChoice\Thrust\Fields;

class Integer extends Decimal
{
    protected function getFieldAttributes()
    {
        return 'step=1';
    }
}
