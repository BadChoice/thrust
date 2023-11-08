<?php

namespace BadChoice\Thrust\Fields;

class SelectEnum extends Select
{
    public function getValue($object)
    {
        return parent::getValue($object)?->value;
    }
}
