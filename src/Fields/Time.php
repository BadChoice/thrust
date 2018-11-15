<?php

namespace BadChoice\Thrust\Fields;

class Time extends Text
{
    protected $withSeconds;
    
    public function withSeconds($withSeconds = true)
    {
        $this->withSeconds = $withSeconds;
        return $this;
    }

    protected function getFieldType()
    {
        return 'time';
    }

    protected function getFieldAttributes()
    {
        return $this->withSeconds ? ' step=1 ' : '';
    }
}
