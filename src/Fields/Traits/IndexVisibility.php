<?php

namespace BadChoice\Thrust\Fields\Traits;

use BadChoice\Thrust\Visibility\Hide;
use BadChoice\Thrust\Visibility\Show;

trait IndexVisibility
{
    public $showIndex;
    public $hideIndex;

    public function makeIndexVisibility()
    {
        $this->hideIndex = new Hide();
        $this->showIndex = new Show();
    }

    public function hideIndexWhen($field, $value = true)
    {
        $this->hideIndex->hideWhen($field,$value);
        return $this;
    }

    public function showIndexWhen($field, $value = true)
    {
        $this->showIndex->showWhen($field, $value);
        return $this;
    }

    public function showIndexCallback($callback)
    {
        $this->showIndex->showCallback($callback);
        return $this;
    }

    public function hideIndexCallback($callback)
    {
         $this->hideIndex->hideCallback($callback);
        return $this;
    }

    public function shouldHideInIndex($object)
    {
        return $this->hideIndex->shouldHide($object);
    }

    public function shouldShowInIndex($object)
    {
        return $this->showIndex->shouldShow($object);
    }
}
