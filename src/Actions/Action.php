<?php

namespace BadChoice\Thrust\Actions;

use Illuminate\Support\Collection;

abstract class Action
{
    public $needsConfirmation   = true;
    public $confirmationMessage = 'Are you sure?';
    public $title               = null;
    public $icon                = null;
    public $main                = false;
    public $needsSelection      = true;

    public $resource;

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
    public function getTitle()
    {
        if ($this->icon) {
            return icon($this->icon) . __("thrust::messages.$this->title");
        }
        return __("thrust::messages.$this->title");
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
}
