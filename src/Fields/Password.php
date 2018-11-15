<?php

namespace BadChoice\Thrust\Fields;

class Password extends Text
{
    public $showInIndex = false;

    public function displayInIndex($object)
    {
        return '******';
    }

    protected function getFieldType()
    {
        return 'password';
    }
}
