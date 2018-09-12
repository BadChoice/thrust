<?php

namespace BadChoice\Thrust\Fields;


class Link extends Field
{
    public $showInEdit = false;
    protected $link = '';
    protected $classes = '';

    public function link($link)
    {
        $this->link = $link;
        return $this;
    }

    public function classes($classes)
    {
        $this->classes = $classes;
        return $this;
    }

    public function displayInIndex($object)
    {
        return view('thrust::fields.link',[
            'class' => $this->classes,
            'value' => $this->getTitle(),
            'url' => str_replace("{field}", $this->getValue($object), $this->link)
        ]);
    }

    public function displayInEdit($object, $inline = false)
    {
        return $this->displayInIndex($object);
    }


}