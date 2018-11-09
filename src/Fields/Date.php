<?php

namespace BadChoice\Thrust\Fields;

class Date extends Text
{
    protected function getFieldType()
    {
        return 'date';
    }
}
