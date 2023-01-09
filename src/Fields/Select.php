<?php

namespace BadChoice\Thrust\Fields;

use Illuminate\Support\Collection;

class Select extends Field
{
    protected $options          = [];
    protected bool $allowNull        = false;
    protected bool $searchable       = false;
    protected bool $forceIntValue    = false;
    protected bool $isEnum    = false;
    protected $attributes       = '';

    public function options(array|Collection $options, bool $allowNull = false)
    {
        $this->options = is_array($options)
            ? $options
            : $options->toArray();
        $this->allowNull = $allowNull;
        return $this;
    }

    public function forceIntValue(bool $forceIntValue = true): self
    {
        $this->forceIntValue = $forceIntValue;
        return $this;
    }

    public function isEnum(bool $isEnum = true): self
    {
        $this->isEnum = $isEnum;
        return $this;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;
        return $this;
    }

    protected function getOptions()
    {
        if ($this->allowNull) {
            return ['' => '--'] + $this->options;
        }
        return $this->options;
    }

    public function allowNull(bool $allowNull = true): self
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    public function displayInIndex($object)
    {
        if ($this->hasCategories()){
            $arrayWithoutCategories = collect($this->getOptions())->mapWithKeys(function($a) { return $a; })->all();
            return $arrayWithoutCategories[$this->getValue($object)] ?? '--';
        }
        return $this->getOptions()[$this->getValue($object)] ?? '--';
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.select', [
            'title'       => $this->getTitle(),
            'inline'      => $inline,
            'field'       => $this->field,
            'searchable'  => $this->searchable,
//            'value'       => intval($this->getValue($object)),
            'value'       => $this->getValue($object),
            'options'     => $this->getOptions(),
            'description' => $this->getDescription(),
            'attributes'  => $this->getFieldAttributes(),
            'hasCategories' => $this->hasCategories(),
        ])->render();
    }

    public function getValue($object)
    {
        $value = parent::getValue($object);
        if ($this->isEnum) {
            $value = $value?->value;
        }
        if ($this->forceIntValue) {
            return intval($value);
        }
        return $value;
    }

    public function attributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    protected function getFieldAttributes()
    {
        return $this->attributes;
    }

    protected function hasCategories()
    {
        $options = $this->getOptions();
        return is_array($options[array_key_first($options)]);
    }
}
