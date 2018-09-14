<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\Fields\Text;
use Illuminate\Support\Collection;

class UpdateValue extends Action
{
    public $needsConfirmation = true;
    public $icon = "pencil";
    public $field = 'name';

    public static function make($field = 'name')
    {
        $action = new static;
        $action->field = $field;
        return $action;
    }

    public function fields()
    {
        return [
            Text::make($this->field)->rules('required|min:3')
        ];
    }

    public function handle(Collection $objects){
        $this->getAllObjectsQuery($objects)->update([
            $this->field => request($this->field)
        ]);
    }

}