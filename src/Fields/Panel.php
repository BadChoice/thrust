<?php

namespace BadChoice\Thrust\Fields;


class Panel
{
    public $fields;
    public $showInEdit = true;

    public static function make($fields){
        $panel = new static;
        $panel->fields = $fields;
        return $panel;
    }

    public function displayInEdit($object){
        return '<div class="formPanel">' .
        collect($this->fields)->where('showInEdit',true)->reduce(function($carry, Field $field) use($object){
            return $carry .$field->displayInEdit($object);
        }) .'</div>';
    }
}