<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\ResourceGate;

class Export extends MainAction
{
    public $title = 'export';

    public function display($resourceName, $parent_id = null)
    {
        if (! app(ResourceGate::class)->can($resourceName, 'index'))
            return "";

        $title = $this->getTitle();
        $link = route('thrust.export', $resourceName);
        return "<a class='button' href='{$link}'> <i class='fa fa-download'></i> {$title} </a>";
    }

    protected function getTitle()
    {
        return __('thrust::messages.' . $this->title);
    }
}