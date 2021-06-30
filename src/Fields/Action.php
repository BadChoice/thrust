<?php

namespace BadChoice\Thrust\Fields;

class Action extends Field {

    protected $action;

    public function displayInIndex($object)
    {
        $className = str_replace("\\", "\\\\", get_class($this->action));
        return "<a class='pointer' onclick='doAction(\"{$className}\", [{$object->id}])'>{$this->action->getTitle()}<a>";
    }

    public function displayInEdit($object, $inline = false)
    {
        return $this->displayInIndex($object);
    }

    public function action($action){
        $this->action = $action;
        return $this;
    }

}