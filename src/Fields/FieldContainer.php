<?php

namespace BadChoice\Thrust\Fields;

abstract class FieldContainer
{
    public $fields;

    public function fieldsFlattened()
    {
        return collect($this->fields)->flatMap->fieldsFlattened();
    }

    public function panels($object)
    {
        return collect($this->fields)->filter(function ($field) {
            return $field instanceof FieldContainer;
        })->flatMap->panels($object);
    }

    public function withObject($object): void {}
}
