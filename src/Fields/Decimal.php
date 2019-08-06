<?php

namespace BadChoice\Thrust\Fields;

class Decimal extends Text
{
    protected $asInteger = false;
    public $rowClass = 'text-right';

    public function getValue($object)
    {
        $value = parent::getValue($object);
        if ($value && $this->asInteger) {
            return number_format(floatval($value) / 100.0, 2);
        }
        return floatval($value);
    }

    public function mapAttributeFromRequest($value)
    {
        if ($this->asInteger) {
            return $value * 100.0;
        }
        return $value;
    }

    public function asInteger($asInteger = true)
    {
        $this->asInteger = $asInteger;
        return $this;
    }

    protected function getFieldType()
    {
        return 'number';
    }

    protected function getFieldAttributes()
    {
        return 'step=any';
    }
}
