<?php

namespace BadChoice\Thrust\Fields;

class EmptyField extends Field
{
    public function __invoke()
    {
        return $this->make('');
    }

    public function displayInIndex($object)
    {
        return '';
    }

    public function displayInEdit($object, $inline = false)
    {
        return '';
    }
}
