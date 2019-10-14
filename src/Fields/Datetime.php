<?php

namespace BadChoice\Thrust\Fields;

use Carbon\Carbon;

class Datetime extends Text
{
    protected function getFieldType()
    {
        return 'datetime-local';
    }

    public function timezone($timezone) {
        $this->timezone = $timezone;
        return $this;
    }

    public function getValue($object)
    {
        if (! $this->timezone) return parent::getValue($object);
        return Carbon::parse(data_get($object, $this->field))->timezone($this->timezone)->toDateTimeString();
    }
}
