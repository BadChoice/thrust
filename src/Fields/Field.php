<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Fields\Traits\Visibility;
use BadChoice\Thrust\Html\Validation;

abstract class Field{

    use Visibility;

    public $field;
    public $sortable = false;
    protected $title;
    public $validationRules;

    public $showInIndex = true;
    public $showInEdit = true;

    public $withDesc = false;
    public $description = false;

    public $rowClass = "";

    public abstract function displayInIndex($object);
    public abstract function displayInEdit($object, $inline = false);

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

    public function rowClass($class){
        $this->rowClass = $class;
        return $this;
    }

    public function sortable($sortable = true)
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function withDesc($withDesc = true, $description = null)
    {
        $this->withDesc = $withDesc;
        $this->description = $description;
        return $this;
    }

    public function getTitle()
    {
        return $this->title ?? trans_choice(config('thrust.translationsPrefix').$this->field, 1);
    }

    public function getDescription(){
        return ($this->withDesc && !$this->description) ? trans_choice(config('thrust.translationsPrefix').$this->field.'Desc', 1) : $this->description;
    }


    protected function getValue($object)
    {
        if (! $object) return null;
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
