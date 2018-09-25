<?php

namespace BadChoice\Thrust\Fields;

class Text extends Field{

    protected $displayInIndexCallback = null;
    protected $editableHint           = false;

    public function editableHint($editableHint = true)
    {
        $this->editableHint = $editableHint;
        return $this;
    }

    public function getIndexClass()
    {
        return $this->editableHint ? "editableHint" : "";
    }

    public function displayWith($callback)
    {
        $this->displayInIndexCallback = $callback;
        return $this;
    }

    public function displayInIndex($object){
        if ($this->displayInIndexCallback){
            return call_user_func($this->displayInIndexCallback, $object);
        }
        return "<span class='{$this->getIndexClass()}'>{$this->getValue($object)}</span>";
    }

    public function displayInEdit($object, $inline = false){
        return view('thrust::fields.input',[
            'inline' => $inline,
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
        return strip_tags(data_get($object, $this->field));
    }

}
