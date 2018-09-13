<?php

namespace BadChoice\Thrust\Actions;

use Illuminate\Support\Collection;

class Deactivate extends Action
{
    public $needsConfirmation = true;
    public $title = 'Deactivate';
    public $icon = 'times';
    public $field = 'active';

    public function handle(Collection $objects){
        $objects->each(function($object){
            $object->update([$this->field => false]);
        });
    }

}