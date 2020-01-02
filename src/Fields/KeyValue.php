<?php

namespace BadChoice\Thrust\Fields;

class KeyValue extends Field {

    public $showInEdit = false;
    protected $keyOptions = null;
    protected $valueOptions = null;

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
        return "";
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.keyValue', [
            'title'         => $this->getTitle(),
            'inline'        => $inline,
            'field'         => $this->field,
            'keyValueField' => $this,
//            'searchable'  => $this->searchable,
            'value'         => $this->getValue($object),
//            'options'     => $this->getOptions(),
            'description'   => $this->getDescription(),
        ])->render();
    }


    public function generateKeyField($iteration, $key = null){
        if (! $this->keyOptions) {
            return "<input type='text' id='$this->field[$iteration][key]' value='$key' name='$this->field[$iteration][key]' placeholder='key' style='width:132px'>";
        }
        $options = $this->generateOptions($this->keyOptions, $key);
        return "<select id='$this->field[$iteration][key]' name='$this->field[$iteration][key]' style='width:132px'>{$options}</select>";
    }

    public function generateValueField($iteration, $value = null){
        if (! $this->valueOptions) {
            return "<input type='text' id='$this->field[$iteration][value]' value='$value' name='$this->field[$iteration][value]' placeholder='value' style='width:132px'>";
        }
        $options = $this->generateOptions($this->valueOptions, $value);
        return "<select id='$this->field[$iteration][value]' name='$this->field[$iteration][value]' style='width:132px'>{$options}</select>";
    }

    private function generateOptions($array, $selected){
        return collect($array)->map(function($value, $key) use($selected) {
            $selected = $key == $selected ? " selected " : "";
            return "<option value='$key' {$selected}>$value</option>";
        })->implode("");
    }
}
