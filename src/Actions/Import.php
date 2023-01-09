<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\ResourceGate;
use Illuminate\Support\Collection;

class Import extends MainAction
{
    public $icon              = 'cloud-upload';

    public function __construct()
    {
        $this->title = 'import';
    }

    public function getClasses()
    {
        return "button secondary";
    }

    public function getAction($resourceName) : string
    {
        return route('thrust.import', $resourceName);
    }
}
