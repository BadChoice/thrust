<?php

namespace BadChoice\Thrust;


class ResourceManager
{
    protected $resourcesFolder = 'Thrust';
    protected $resources;

    public function __construct(){
        $this->findResources();
    }

    private function findResources()
    {
        $folder = app_path() . '/' . $this->resourcesFolder;
        $this->resources = collect(scandir($folder))->filter(function ($filename) {
            return str_contains($filename, ".php");
        })->mapWithKeys(function ($filename) {
            $resource = substr($filename, 0, -4);
            return [str_plural(strtolower(substr($filename, 0, -4))) => "\\App\\Thrust\\" . $resource];
        });
    }

    /**
     * @param $resourceName
     * @return Resource
     */
    public function make($resourceName)
    {
        $type = strtolower($resourceName);
        $class = $this->resources[$type];
        return new $class;
    }

    /**
     * @param $class
     * @return string
     */
    public function resourceNameFromModel($class)
    {
        if (! is_string($class)) {
            $class = get_class($class);
        }
        $path = explode('\\', $class);
        $name = array_pop($path);
        return strtolower(str_plural($name)) ;
    }

}