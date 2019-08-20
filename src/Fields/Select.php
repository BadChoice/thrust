<?php

namespace BadChoice\Thrust\Fields;

class Select extends Field
{
    protected $options    = [];
    protected $allowNull  = false;
    protected $searchable = false;
    protected $forceIntValue = false;

    public function options($options, $allowNull = false)
    {
        $this->options   = $options;
        $this->allowNull = $allowNull;
        return $this;
    }

    public function forceIntValue($forceIntValue = true){
        $this->forceIntValue = $forceIntValue;
        return $this;
    }

    public function searchable($searchable = true)
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

    public function allowNull($allowNull = true)
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    public function displayInIndex($object)
    {
        return $this->getOptions()[$this->getValue($object)] ?? '--';
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.select', [
            'title'       => $this->getTitle(),
            'inline'      => $inline,
            'field'       => $this->field,
            'searchable'  => $this->searchable,
            'value'       => intval($this->getValue($object)),
            'options'     => $this->getOptions(),
            'description' => $this->getDescription(),
        ])->render();
    }

    public function getValue($object)
    {
        if ($this->forceIntValue) {
            return intval(parent::getValue($object));
        }
        return parent::getValue($object);
    }
}
