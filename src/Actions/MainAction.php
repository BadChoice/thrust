<?php

namespace BadChoice\Thrust\Actions;

class MainAction
{
    public $title;

    public static function make($title)
    {
        $action = new static;
        $action->title = $title;
        return $action;
    }

    public function display($resourceName)
    {
        $title = __('thrust::messages.'.$this->title);
        $link = route('thrust.create', $resourceName);
        return "<a class='button showPopup' href='{$link}'> <i class='fa fa-plus'></i> {$title} </a>";
    }

}