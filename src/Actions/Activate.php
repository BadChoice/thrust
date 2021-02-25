<?php

namespace BadChoice\Thrust\Actions;

use Illuminate\Support\Collection;

class Activate extends Action
{
    public $needsConfirmation = true;
    public $icon              = 'check';
    public $title             = 'activate';
    public $field             = 'active';

    public function handle(Collection $objects)
    {
        $this->getAllObjectsQuery($objects)->update([
            $this->field => true
        ]);
    }

}
