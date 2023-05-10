<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\ResourceGate;

class MainAction
{
    public $title;
    public $icon;

    public static function make($title)
    {
        $action        = new static;
        $action->title = $title;
        $action->icon  = 'plus';
        return $action;
    }

    public function display($resourceName, $parent_id = null)
    {
        if (! app(ResourceGate::class)->can($resourceName, 'create')) {
            return '';
        }

        $title = $this->getTitle();
        $link  = $this->getAction($resourceName);
        if ($parent_id) {
            $link .= "?parent_id={$parent_id}";
        }
        return "<a class='{$this->getClasses()}' href='{$link}'> {$this->getIcon()} {$title} </a>";
    }

    public function getClasses()
    {
        return 'button showPopup';
    }

    protected function getIcon() : string
    {
        return $this->icon
            ? "<i class='fa fa-{$this->icon}'></i>"
            : '';
    }

    protected function getAction($resourceName) : string
    {
        return route('thrust.create', $resourceName);
    }

    protected function getTitle()
    {
        return __('thrust::messages.' . $this->title);
    }
}
