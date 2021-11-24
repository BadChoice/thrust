<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Facades\Thrust;

class Delete extends Field {

    public $showInEdit          = false;
    public $withoutIndexHeader  = true;
    public $rowClass            = 'action';
    public $policyAction        = 'delete';

    public function displayInIndex($object)
    {
        return $this->renderDeleteWithConfirm($object);
    }

    protected function renderDeleteWithConfirm($object) {
        $link = route('thrust.delete', [Thrust::resourceNameFromModel($object), $object->id]);
        $escapedConfirmMessage = htmlentities($this->getDeleteConfirmationMessage(), ENT_QUOTES);
        return "<a class='delete-resource thrust-delete'".
            ( $this->deleteConfirmationMessage ? "data-delete='resource confirm' confirm-message='{$escapedConfirmMessage}'" : "data-delete='resource'") .
            " href='{$link}'></a>";
    }

    public function displayInEdit($object, $inline = false){ }

}