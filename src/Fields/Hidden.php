<?php

namespace BadChoice\Thrust\Fields;

class Hidden extends Text
{
    public $showInIndex = false;

    protected function getFieldType()
    {
        return 'hidden';
    }

    public function displayInEdit($object, $inline = false)
    {
        $form = $inline
            ? "form=\"{$this->getFormId($object)}\""
            : '';
        return "<input type='hidden' name='{$this->field}' value='{$this->getValue($object)}' {$form}>";
    }
}
