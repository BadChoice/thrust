<?php

namespace BadChoice\Thrust\Fields;

class Panel
{
    public $fields;
    public $showInEdit = true;
    public $title;
    public $icon;
    public $panelId;
    public $panelClass = 'formPanel';

    public $hideWhenField;
    public $hideWhenValue;

    public static function make($fields, $title = null)
    {
        $panel         = new static;
        $panel->fields = $fields;
        $panel->title  = $title;
        return $panel;
    }

    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function panelId($id)
    {
        $this->panelId = $id;
        return $this;
    }

    public function displayInEdit($object, $inline = false)
    {
        $html = '<div class="'. $this->panelClass .'" id="panel_'.$this->getId().'" title="'. $this->title .'">';
        $html .= $this->getTitle();
        return $html . collect($this->fields)->where('showInEdit', true)->reduce(function ($carry, Field $field) use ($object) {
            return $carry .$field->displayInEdit($object);
        }) .'</div>';
    }

    protected function getTitle()
    {
        if (! $this->title && ! $this->icon) {
            return '';
        }
        return implode('', ['<h4>', icon($this->icon ?? ''), ' ', $this->title, '</h4>']);
    }

    public function hideWhen($field, $value = true)
    {
        $this->hideWhenField = $field;
        $this->hideWhenValue = $value;
        collect($this->fields)->each(function ($field) {
            $field->hideWhenField = $this->hideWhenField;
            $field->hideWhenValue = $this->hideWhenValue;
        });
        return $this;
    }

    public function getId()
    {
        return $this->panelId ?? $this->title;
    }

}
