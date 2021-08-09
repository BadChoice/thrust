<?php

namespace BadChoice\Thrust\Fields;

abstract class FieldContainer
{
    public $fields;

    public function fieldsFlattened()
    {
        return collect($this->fields)->flatMap->fieldsFlattened();
    }

    public function panels()
    {
        return collect($this->fields)->filter(function ($field) {
            return $field instanceof FieldContainer;
        })->flatMap->panels();
    }
}
