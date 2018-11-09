<?php

namespace BadChoice\Thrust\Actions;

use Illuminate\Support\Collection;

class Delete extends Action
{
    public $needsConfirmation = true;
    public $icon              = 'trash';
//    public $main = true;
    public $main = false;

    public function handle(Collection $objects)
    {
        $objects->each(function ($object) {
            $this->resource->delete($object);
        });
    }
}
