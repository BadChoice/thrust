<?php

namespace BadChoice\Thrust\Actions;

class CreateMultiple extends MainAction
{
    public static function make($title)
    {
        $action       = parent::make($title);
        $action->icon = null;
        return $action;
    }

    protected function getAction($resourceName) : string
    {
        return route('thrust.create.multiple', $resourceName);
    }

    protected function getTitle(): string
    {
        return __('thrust::messages.createMultiple');
    }
}
