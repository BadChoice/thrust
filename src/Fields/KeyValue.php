<?php

namespace BadChoice\Thrust\Fields;

class KeyValue extends Field {
    public $showInEdit = false;

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
        return "<input type='text' id='$this->field[$iteration][key]' value='$key' name='$this->field[$iteration][key]' placeholder='key' style='width:132px'>";
    }

    public function generateValueField($iteration, $value = null){
        return "<input type='text' id='$this->field[$iteration][value]' value='$value' name='$this->field[$iteration][value]' placeholder='value' style='width:132px'>";
    }
}
