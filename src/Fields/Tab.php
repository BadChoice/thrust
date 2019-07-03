<?php

namespace BadChoice\Thrust\Fields;

class Tab extends Panel
{
    public $panelClass = 'formTab';

    public static function make($fields, $title = null)
    {
        $panel = parent::make($fields, $title)->panelId(str_replace(" ", "-", $title));
        return $panel;
    }

    public function getTitle()
    {
        return "<h4></h4>";
    }
}
