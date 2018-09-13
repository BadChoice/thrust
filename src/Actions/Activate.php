<?php

namespace BadChoice\Thrust\Actions;

use Illuminate\Support\Collection;

class Activate extends Action
{
    public $needsConfirmation = true;
    public $icon = 'check';
    public $title = 'Activate';
    public $field = 'active';

    public function handle(Collection $objects){
        $objects->each(function($object){
            $object->update([$this->field => true]);
        });
    }

}