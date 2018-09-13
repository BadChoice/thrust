<?php

namespace BadChoice\Thrust\Actions;


use Illuminate\Support\Collection;

abstract class Action
{
    public $needsConfirmation = true;
    public $confirmationMessage = "Are you sure?";
    public $title = null;
    public $icon = null;
    public $main = false;

    public abstract function handle(Collection $objects);

    /**
     * Should return the fields required for the action so they will be asked
     * @return array
     */
    public function fields(){
        return [];
    }

    public function getClassForJs()
    {
        return str_replace("\\", "\\\\", get_class($this));
    }

    public function getTitle()
    {
        $title = $this->title ?? collect(explode("\\", get_class($this)))->last();
        if ($this->icon)
            return icon($this->icon) . " " . $title ;
        return $title;
    }
}