<?php

namespace BadChoice\Thrust\Fields;

class ParentId extends Text
{
    public $showInIndex = false;

    protected function getFieldType()
    {
        return 'hidden';
    }

    public function getValue($object)
    {
        $parent_id = parent::getValue($object);
        return strlen($parent_id) == 0 ? request('parent_id') : $parent_id;
    }

    public function displayInEdit($object, $inline = false)
    {
        return "<input type='hidden' name='{$this->field}' value='{$this->getValue($object)}'>";
    }
}
