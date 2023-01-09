<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Facades\Thrust;

class Edit extends Field {

    public $showInEdit          = false;
    public $withoutIndexHeader  = true;
    public $rowClass            = 'action';
    public $policyAction        = 'update';
    public $importable          = false;

    public function displayInIndex($object)
    {
        $link = route('thrust.edit', [Thrust::resourceNameFromModel($object), $object->id]);
        return "<a class='showPopup edit thrust-edit' href='{$link}'></a> </td>";
    }

    public function displayInEdit($object, $inline = false){ }

}