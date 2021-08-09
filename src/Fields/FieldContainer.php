<?php

namespace BadChoice\Thrust\Fields;

abstract class FieldContainer
{
    public $fields;

    public function fieldsFlattened()
    {
        return collect($this->fields)->map(function ($field) {
            if ($field instanceof FieldContainer) {
                return $field->fieldsFlattened();
            }
            return $field;
        })->flatten();
    }

    public function panels()
    {
        return collect($this->fields)->filter(function ($field) {
            return $field instanceof FieldContainer;
        })->map->panels()->flatten();
    }
}
