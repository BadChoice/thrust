<?php

namespace BadChoice\Thrust\Fields;

class Text extends Field{

    protected $displayInIndexCallback = null;

    public function displayInIndex($object){
        if ($this->displayInIndexCallback){
            return call_user_func($this->displayInIndexCallback, $object);
        }
        return $this->getValue($object);
    }

    public function displayWith($callback)
    {
        $this->displayInIndexCallback = $callback;
        return $this;
    }

    public function displayInEdit($object){
        return view('thrust::fields.input',[
            'title' => $this->getTitle(),
            'type' => $this->getFieldType(),
            'field' => $this->field,
            'value' => $this->getValue($object),
            'validationRules' => $this->getHtmlValidation($object, $this->getFieldType()),
            'attributes' => $this->getFieldAttributes(),
            'description' => $this->getDescription(),
        ])->render();
    }

    protected function getFieldType(){
        return 'text';
    }

    protected function getFieldAttributes(){
        return '';
    }

    protected function getValue($object)
    {
        if (! $object) return null;
        return strip_tags($object->{$this->field});
    }

}
