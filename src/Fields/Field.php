<?php

namespace BadChoice\Thrust\Fields;

abstract class Field{

    protected $field;
    protected $title;

    public abstract function displayInIndex($object);

    public static function make($dbField, $title = null)
    {
        $field = new static;
        $field->field = $dbField;
        $field->title = $title;
        return $field;
    }

    public function getTitle()
    {
        return $this->title ?? __($this->field);
    }

}
