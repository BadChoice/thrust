<?php

namespace BadChoice\Thrust\Actions;


use Illuminate\Support\Collection;

abstract class Action
{
    public $needsConfirmation = true;
    public $confirmationMessage = "Are you sure?";
    public $title = null;
    public $icon = null;

    public abstract function handle(Collection $objects);

    public function getClassForJs()
    {
        return str_replace("\\", "\\\\", get_class($this));
    }

    public function getTitle()
    {
        if ($this->icon)
            return icon($this->icon);
        if ($this->title)
            return $this->title;
        return collect(explode("\\", get_class($this)))->last();
    }
}