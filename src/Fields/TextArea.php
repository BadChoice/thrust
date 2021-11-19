<?php

namespace BadChoice\Thrust\Fields;

class TextArea extends Field
{
    protected $attributes         = '';
    protected $shouldAllowScripts = false;

    public $showInIndex = false;

    public function displayInIndex($object)
    {
        return $this->getValue($object);
    }

    protected function getFieldAttributes()
    {
        return 'cols=50 rows=10'.' '.$this->attributes;
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.textarea', [
            'inline'          => $inline,
            'title'           => $this->getTitle(),
            'field'           => $this->field,
            'value'           => $this->getValue($object),
            'validationRules' => $this->getHtmlValidation($object, 'textarea'),
            'attributes'      => $this->getFieldAttributes(),
            'description'     => $this->getDescription(),
            'formId'          => $this->getFormId($object),
        ])->render();
    }

    public function getValue($object)
    {
        return htmlspecialchars($object->{$this->field});
    }

    public function allowScripts()
    {
        $this->shouldAllowScripts = true;
        return $this;
    }

    public function mapAttributeFromRequest($value)
    {
        return parent::mapAttributeFromRequest(! $this->shouldAllowScripts ? strip_tags($value) : $value);
    }

    public function attributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }
}
