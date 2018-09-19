<?php

namespace BadChoice\Thrust\Fields;


class Link extends Field
{
    public $showInEdit = false;
    protected $link = '';
    protected $route;
    protected $classes = '';
    protected $icon = '';

    public function link($link)
    {
        $this->link = $link;
        return $this;
    }

    public function route($route)
    {
        //TODO: Make it work with parameters
        $this->route = $route;
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
            'icon' => $this->icon,
            'value' => $this->getTitle(),
            'url' => $this->getUrl($object)
        ]);
    }

    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getUrl($object)
    {
        if ($this->route){
            return route($this->route, [$object]);
        }
        return str_replace("{field}", $this->getValue($object), $this->link);
    }

    public function displayInEdit($object, $inline = false)
    {
        return $this->displayInIndex($object);
    }


}