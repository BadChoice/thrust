<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\ResourceGate;
use Illuminate\Support\Collection;

class Delete extends Action
{
    public $needsConfirmation = true;
    public $icon              = 'trash';
//    public $main = true;
    public $main = false;

    public function handle(Collection $objects)
    {
        $gate = app(ResourceGate::class);
        $objects->each(function ($object) use($gate) {
            if ($gate->can($this->resource, 'delete', $object)) {
                $this->resource->delete($object);
            }
        });
    }
    public function getTitle()
    {
        if ($this->icon) {
            return icon($this->icon) . __("thrust::messages.delete");
        }
        return __("thrust::messages.delete");
    }

}
