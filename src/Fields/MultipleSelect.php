<?php

namespace BadChoice\Thrust\Fields;

class MultipleSelect extends Select
{
    protected $options    = [];
    protected $allowNull  = false;
    protected $searchable = false;
    protected $isMultiple = true;
    protected $clearable  = false;

    public function displayInIndex($object)
    {
        return collect($this->getValue($object))->map(function ($value) {
            return $this->getOptions()[$value];
        })->implode(', ');
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.multipleSelect', [
            'title'       => $this->getTitle(),
            'inline'      => $inline,
            'field'       => $this->field,
            'searchable'  => $this->searchable,
            'value'       => $this->getValue($object),
            'options'     => $this->getOptions(),
            'description' => $this->getDescription(),
            'clearable'   => $this->clearable,
            'formId'      => $this->getFormId($object),
        ])->render();
    }

    public function clearable($clearable = true)
    {
        $this->clearable = $clearable;
        return $this;
    }
}
