<?php

namespace BadChoice\Thrust\Actions;

use Illuminate\Support\Collection;

class Deactivate extends Action
{
    public $needsConfirmation = true;
    public $icon              = 'times';
    public $field             = 'active';
    public function __construct()
    {
        $this->title = __('thrust::messages.deactivate');
    }

    public function handle(Collection $objects)
    {
        $this->getAllObjectsQuery($objects)->update([
            $this->field => false
        ]);
        /*$objects->each(function($object){
            $object->update([$this->field => false]);
        });*/
    }
}
