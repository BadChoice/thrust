<?php

namespace BadChoice\Thrust\Fields;

class Color extends Text
{
    public $blockSize = '20';
    protected $colorIndexCallback;

    public function displayInIndex($object)
    {
        $color = $this->getValue($object);
        if (! $color && $this->colorIndexCallback) {
            $color = call_user_func($this->colorIndexCallback, $object) ?? $color;
        }
        return "<div class='circle inline mr1' style='vertical-align:bottom; width:{$this->blockSize}px; height:{$this->blockSize}px; background-color:{$color}'></div>{$this->getValue($object)}";
    }

    public function colorIndexCallback($colorCallback)
    {
        $this->colorIndexCallback = $colorCallback;
        return $this;
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.color', [
            'field'       => $this->field,
            'value'       => $this->getValue($object),
            'description' => $this->getDescription(),
            'title'       => $this->getTitle(),
            'inline'      => $inline,
        ]);
    }

    protected function getFieldType()
    {
        return 'color';
    }
}
