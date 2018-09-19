<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\ResourceGate;

class MainAction
{
    public $title;

    public static function make($title)
    {
        $action = new static;
        $action->title = $title;
        return $action;
    }

    public function display($resourceName, $parent_id = null)
    {
        if (! app(ResourceGate::class)->can($resourceName, 'create'))
            return "";

        $title = __('thrust::messages.'.$this->title);
        $link = route('thrust.create', $resourceName);
        if ($parent_id){
            $link .= "?parent_id={$parent_id}";
        }
        return "<a class='button showPopup' href='{$link}'> <i class='fa fa-plus'></i> {$title} </a>";
    }

}