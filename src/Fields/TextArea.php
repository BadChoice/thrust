<?php

namespace BadChoice\Thrust\Fields;

class TextArea extends Field
{
    public $showInIndex = false;

    public function displayInIndex($object)
    {
        return $this->getValue($object);
    }

    protected function getFieldAttributes()
    {
        return 'cols=50 rows=10';
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.textarea', [
            'title'           => $this->getTitle(),
            'field'           => $this->field,
            'value'           => $this->getValue($object),
            'validationRules' => $this->getHtmlValidation($object, 'textarea'),
            'attributes'      => $this->getFieldAttributes()
        ])->render();
    }

    public function getValue($object)
    {
        return strip_tags($object->{$this->field});
    }
}
