<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Html\Validation;

abstract class Field{

    public $field;
    public $sortable = false;
    protected $title;
    public $validationRules;

    public $showInIndex = true;
    public $showInEdit = true;

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

    protected function getValue($object)
    {
        return $object->{$this->field};
    }

    public function getHtmlValidation($object, $type) {
        return Validation::make($this->validationRules, $type)->generate();
    }

    public function onlyInIndex(){
        $this->showInIndex = true;
        $this->showInEdit = false;
        return $this;
    }

    public function hideInIndex(){
        $this->showInIndex = false;
        return $this;
    }

    public function hideInEdit(){
        $this->showInEdit = false;
        return $this;
    }

    public function onlyInEdit(){
        $this->showInIndex = false;
        $this->showInEdit = true;
        return $this;
    }

}
