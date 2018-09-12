<?php

namespace BadChoice\Thrust\Fields;


class Panel
{
    public $fields;
    public $showInEdit = true;
    public $title;

    public $hideWhenField;
    public $hideWhenValue;

    public static function make($fields, $title = null){
        $panel = new static;
        $panel->fields = $fields;
        $panel->title = $title;
        return $panel;
    }

    public function displayInEdit($object, $inline = false){
        return '<div class="formPanel" id="panel_'.$this->title.'">' .
        collect($this->fields)->where('showInEdit',true)->reduce(function($carry, Field $field) use($object){
            return $carry .$field->displayInEdit($object);
        }) .'</div>';
    }

    public function hideWhen($field, $value = true)
    {
        $this->hideWhenField = $field;
        $this->hideWhenValue = $value;
        collect($this->fields)->each(function($field){
            $field->hideWhenField = $this->hideWhenField;
            $field->hideWhenValue = $this->hideWhenValue;
        });
        return $this;
    }

}