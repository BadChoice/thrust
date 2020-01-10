<?php

namespace BadChoice\Thrust\Fields\Traits;

use BadChoice\Thrust\Visibility\Hide;
use BadChoice\Thrust\Visibility\Show;

trait EditVisibility
{
    public $showEdit;
    public $hideEdit;

    public function makeEditVisibility()
    {
        $this->hideEdit = new Hide();
        $this->showEdit = new Show();
    }

    public function hideEditWhen($field, $value = true)
    {
        $this->hideEdit->hideWhen($field,$value);
        return $this;
    }

    public function showEditWhen($field, $value = true)
    {
        $this->showEdit->showWhen($field, $value);
        return $this;
    }

    public function showEditCallback($callback)
    {
        $this->showEdit->showCallback($callback);
        return $this;
    }

    public function hideEditCallback($callback)
    {
        $this->hideEdit->hideCallback($callback);
        return $this;
    }

    public function shouldHideInEdit($object)
    {
        return $this->hideEdit->shouldHide($object);
    }

    public function shouldShowInEdit($object)
    {
        return $this->showEdit->shouldShow($object);
    }
}
