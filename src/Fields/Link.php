<?php

namespace BadChoice\Thrust\Fields;

class Link extends Field
{
    public $showInEdit = false;
    protected $link    = '';
    protected $route;
    protected $classes      = '';
    protected $icon         = '';
    protected $displayCount = false;
    protected $displayCallback;
    protected $attributes;

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

    public function attributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function displayCallback($displayCallback)
    {
        $this->displayCallback = $displayCallback;
        return $this;
    }

    public function displayInIndex($object)
    {
        if ($this->displayCallback) {
            $value = call_user_func($this->displayCallback, $object);
            return "<a href='{$this->getUrl($object)}' class='{$this->classes}'>{$value}</a>";
        }
        return view('thrust::fields.link', [
            'class'        => $this->classes,
            'icon'         => $this->icon,
            'value'        => $this->getTitle(),
            'displayCount' => $this->displayCount,
            'url'          => $this->getUrl($object),
            'attributes'   => $this->attributes,
        ])->render();
    }

    public function icon($icon)
    {
        $this->icon     = $icon;
        $this->rowClass = $this->rowClass . ' action';
        return $this;
    }

    public function getUrl($object)
    {
        if ($this->route) {
            return route($this->route, [$object]);
        }
        return str_replace('{field}', $this->getValue($object), $this->link);
    }

    public function displayInEdit($object, $inline = false)
    {
        return $this->displayInIndex($object);
    }
}
