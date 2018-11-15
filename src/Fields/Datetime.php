<?php

namespace BadChoice\Thrust\Fields;

class Datetime extends Text
{
    protected function getFieldType()
    {
        return 'datetime-local';
    }
}
