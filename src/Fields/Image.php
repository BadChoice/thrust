<?php

namespace BadChoice\Thrust\Fields;

class Image extends Field
{
    protected $basePath;
    protected $basePathBindings = [];
    protected $classes = 'gravatar';
    protected $gravatarField;

    public function gravatar($field = 'email')
    {
        $this->gravatarField = $field;
        return $this;
    }

    public function path($path, $bindings = [])
    {
        $this->basePath = $path;
        $this->basePathBindings = $bindings;
        return $this;
    }

    public function displayInIndex($object)
    {
        if ($this->getValue($object)) {
            $photoPath = url($this->getBasePath() . "resized_" . $this->getValue($object));
            return "<img src='{$photoPath}' class='$this->classes' style='height:30px; width:30px; object-fit: cover;'>";
        }
        if ($this->gravatarField){
            return Gravatar::make($this->gravatarField)->displayInIndex($object);
        }
        return "+";
    }

    public function displayInEdit($object, $inline = false)
    {

    }

    protected function getBasePath(){
        if (! $this->basePath) return storage_path('thrust');
        return str_replace("{user}", auth()->user()->username, $this->basePath);
    }

}