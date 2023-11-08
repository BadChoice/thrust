<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\Helpers\Iconable;
use BadChoice\Thrust\Helpers\Titleable;
use BadChoice\Thrust\Helpers\Translation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class Action
{
    use Titleable;
    use Iconable;

    public $needsConfirmation   = true;

    /* If when finishing the action, the page should be reloaded*/
    public $shouldReload        = true;
    
    /*If the response should be show in a popup*/
    public $responseAsPopup     = false;
    public $confirmationMessage = 'Are you sure';
    public $main                = false;
    public $needsSelection      = true;

    public $resource;

    protected $selectedTargets;

    abstract public function handle(Collection $objects);

    /**
     * Should return the fields required for the action so they will be asked
     * @return array
     */
    public function fields()
    {
        return [];
    }

    public function getClassForJs()
    {
        return str_replace('\\', '\\\\', get_class($this));
    }

    /**
     * If the action needs to perform an update to all objects, this query can be used to do it
     * with just one query
     * @param $objects
     * @return mixed
     */
    public function getAllObjectsQuery($objects)
    {
        return $objects->first()->query()->whereIn('id', $objects->pluck('id')->toArray());
    }

    public function setSelectedTargets($targets)
    {
        $this->selectedTargets = $targets;
        return $this;
    }

    public function getConfirmationMessage(): string
    {
        return Translation::translate($this->confirmationMessage);
    }
}