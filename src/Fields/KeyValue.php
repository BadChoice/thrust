<?php

namespace BadChoice\Thrust\Fields;

class KeyValue extends Field
{
    public $showInEdit   = false;
    public $keyOptions   = null;
    public $valueOptions = null;
    public $searchable   = false;
    public $multiple     = false;
    public $fixed        = false;

    public function keyOptions($keyOptions)
    {
        $this->keyOptions = $keyOptions;
        return $this;
    }

    public function valueOptions($valueOptions)
    {
        $this->valueOptions = $valueOptions;
        return $this;
    }

    public function displayInIndex($object)
    {
        return '';
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.keyValue', [
            'title'         => $this->getTitle(),
            'inline'        => $inline,
            'field'         => $this->field,
            'keyValueField' => $this,
            'searchable'    => $this->searchable,
            'value'         => $this->getValue($object),
            'description'   => $this->getDescription(),
            'multiple'      => $this->multiple,
            'fixed'         => $this->fixed,
        ])->render();
    }

    public function multiple($multiple = true)
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function searchable($searchable = true)
    {
        $this->searchable = $searchable;
        return $this;
    }

    public function fixedEntries($fixed = true)
    {
        $this->fixed = $fixed;
        return $this;
    }

    public function generateOptions($array, $selected)
    {
        return collect($array)->map(function ($value, $key) use ($selected) {
            $selected = $this->isSelected($key, $selected) ? ' selected ' : '';
            return "<option value='$key' {$selected}>$value</option>";
        })->implode('');
    }

    protected function isSelected($key, $selected) : bool
    {
        return is_array($selected)
            ? in_array($key, $selected)
            : $key == $selected;
    }

    public function mapAttributeFromRequest($value)
    {
        return collect($value)->map(function ($entry) {
            if (! isset($entry['value'])) {
                $entry['value'] = null;
            }
            return $entry;
        });
    }
}
