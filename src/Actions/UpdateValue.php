<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\Fields\Text;
use Illuminate\Support\Collection;

class UpdateValue extends Action
{
    public $needsConfirmation = true;
    public $icon = "pencil";
    public $field = 'name';

    public function fields()
    {
        return [
            Text::make($this->field)->rules('required|min:3')
        ];
    }

    public function handle(Collection $objects){
        $objects->each(function($object){
            $object->update([$this->field => request($this->field)]);
        });
    }

}