<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Fields\Traits\Visibility;
use BadChoice\Thrust\Html\Validation;

abstract class Field
{
    use Visibility;

    public $field;
    public $sortable = false;
    protected $title;
    public $validationRules;

    public $showInIndex = true;
    public $showInEdit  = true;
    public $policyAction = null;

    public $withDesc    = false;
    public $description = false;

    public $withoutIndexHeader = false;
    public $rowClass = '';

    abstract public function displayInIndex($object);

    abstract public function displayInEdit($object, $inline = false);

    public static function make($dbField, $title = null)
    {
        $field        = new static;
        $field->field = $dbField;
        $field->title = $title;
        return $field;
    }

    public function rules($validationRules)
    {
        $this->validationRules = $validationRules;
        return $this;
    }

    public function rowClass($class)
    {
        $this->rowClass = $class;
        return $this;
    }

    public function withoutIndexHeader($withoutIndexHeader = true)
    {
        $this->withoutIndexHeader = $withoutIndexHeader;
        return $this;
    }

    public function sortable($sortable = true)
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function withDesc($withDesc = true, $description = null)
    {
        $this->withDesc    = $withDesc;
        $this->description = $description;
        return $this;
    }

    public function getTitle($forHeader = false)
    {
        if ($forHeader && $this->withoutIndexHeader) return "";
        return $this->title ?? trans_choice(config('thrust.translationsPrefix').$this->field, 1);
    }

    public function getDescription()
    {
        return ($this->withDesc && ! $this->description) ? trans_choice(config('thrust.translationsPrefix').$this->field.'Desc', 1) : $this->description;
    }

    public function getValue($object)
    {
        if (! $object) {
            return null;
        }
        if (str_contains($this->field, '.')) {
            return data_get($object, $this->field);
        }
        if (str_contains($this->field, '[')){
            $this->field = str_replace("]","",str_replace("[",".", $this->field));
            return data_get($object, $this->field);
        }
        return $object->{$this->field};
    }

    public function getHtmlValidation($object, $type)
    {
        return Validation::make($this->validationRules, $type)->generate();
    }

    public function onlyInIndex()
    {
        $this->showInIndex = true;
        $this->showInEdit  = false;
        return $this;
    }

    public function hide($hide = true)
    {
        $this->showInIndex = !$hide;
        $this->showInEdit  = !$hide;
        return $this;
    }

    public function show($show = true)
    {
        $this->showInIndex = $show;
        $this->showInEdit  = $show;
        return $this;
    }

    public function hideInIndex()
    {
        $this->showInIndex = false;
        return $this;
    }

    public function hideInEdit($hideInEdit = true)
    {
        $this->showInEdit = ! $hideInEdit;
        return $this;
    }

    public function onlyInEdit()
    {
        $this->showInIndex = false;
        $this->showInEdit  = true;
        return $this;
    }

    public function policyAction($policyAction)
    {
        $this->policyAction = $policyAction;
        return $this;
    }

    public function mapAttributeFromRequest($value)
    {
        return $value;
    }

    public function getDatabaseField()
    {
        return $this->field;
    }

    public function getSortableHeaderClass(){
        if (str_contains($this->rowClass, 'text-right')) return 'sortableHeaderRight';
        return 'sortableHeader';
    }
}
