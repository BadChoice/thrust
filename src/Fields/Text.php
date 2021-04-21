<?php

namespace BadChoice\Thrust\Fields;

class Text extends Field
{
    protected $displayInIndexCallback = null;
    protected $editableHint           = false;
    protected $attributes             = '';
    protected $shouldAllowScripts     = false;

    public function editableHint($editableHint = true)
    {
        $this->editableHint = $editableHint;
        return $this;
    }

    public function getIndexClass()
    {
        return $this->editableHint ? 'editableHint' : '';
    }

    public function displayWith($callback)
    {
        $this->displayInIndexCallback = $callback;
        return $this;
    }

    public function displayInIndex($object)
    {
        if ($this->displayInIndexCallback) {
            return call_user_func($this->displayInIndexCallback, $object);
        }
        return "<span class='{$this->getIndexClass()}'>{$this->getValue($object)}</span>";
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.input', [
            'inline'          => $inline,
            'title'           => $this->getTitle(),
            'type'            => $this->getFieldType(),
            'field'           => $this->field,
            'value'           => htmlspecialchars_decode($this->getValue($object)),
            'validationRules' => $this->getHtmlValidation($object, $this->getFieldType()),
            'attributes'      => $this->getFieldAttributes(),
            'description'     => $this->getDescription(),
        ])->render();
    }

    protected function getFieldType()
    {
        if ( strpos(strtolower($this->field), 'password') !== false ) {
            return 'password';
        }
        return 'text';
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

    public function getValue($object)
    {
        if (! $object) {
            return null;
        }
        return htmlspecialchars(parent::getValue($object));
    }

    public function allowScripts()
    {
        $this->shouldAllowScripts = true;
        return $this;
    }

    public function mapAttributeFromRequest($value)
    {
        return parent::mapAttributeFromRequest(!$this->shouldAllowScripts ? strip_tags($value) : $value);
    }
}
