<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Html\Validation;

abstract class Field{

    public $field;
    public $sortable = false;
    protected $title;
    protected $validationRules;

    public abstract function displayInIndex($object);
    public abstract function displayInEdit($object);

    public static function make($dbField, $title = null)
    {
        $field = new static;
        $field->field = $dbField;
        $field->title = $title;
        return $field;
    }

    public function rules($validationRules){
        $this->validationRules = $validationRules;
        return $this;
    }

    public function sortable($sortable = true)
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function getTitle()
    {
        return $this->title ?? __(config('thrust.translationsPrefix').$this->field);
    }

    public function getHtmlValidation($object, $type) {
        return Validation::make($this->validationRules, $type)->generate();
    }

}
