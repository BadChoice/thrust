<?php

namespace BadChoice\Thrust\Fields;

class CheckSwitch extends Check
{
    protected $asSwitch = true;

    public function displayInEdit($object, $inline = false)
    {
        return view($inline ? 'thrust::fields.check' : 'thrust::fields.checkSwitch', [
            'title'       => $this->getTitle(),
            'field'       => $this->field,
            'value'       => $this->getValue($object),
            'inline'      => $inline,
            'description' => $this->getDescription(),
        ]);
    }
}
