<?php

namespace BadChoice\Thrust\Fields;

class Email extends Text
{
    public function displayInIndex($object)
    {
        $value = $this->getValue($object);
        return "<a href='mailto:$value'>$value</a>" ;
    }

    protected function getFieldType()
    {
        return 'email';
    }
}
