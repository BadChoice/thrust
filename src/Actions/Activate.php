<?php

namespace BadChoice\Thrust\Actions;

use Illuminate\Support\Collection;

class Activate extends Action
{
    public $needsConfirmation = true;
    public $icon              = 'check';
    public $title             = 'Activate';
    public $field             = 'active';

    public function handle(Collection $objects)
    {
        $this->getAllObjectsQuery($objects)->update([
            $this->field => true
        ]);
    }
    public function getTitle()
    {
        if ($this->icon) {
            return icon($this->icon) . __("thrust::messages.activate");
        }
        return __("thrust::messages.activate");
    }
}
