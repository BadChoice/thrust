<?php

namespace BadChoice\Thrust;

class ResourceManager
{
    protected $resourcesFolder = 'Thrust';
    protected $resources;
    protected static $servingCallback;

    public function __construct()
    {
        $this->findResources();
        if (static::$servingCallback) {
            call_user_func(static::$servingCallback);
        }
    }

    public static function serving($servingCallback)
    {
        static::$servingCallback = $servingCallback;
    }

    private function findResources()
    {
        $folder          = app_path() . '/' . $this->resourcesFolder;
        $this->resources = collect(scandir($folder))->filter(function ($filename) {
            return str_contains($filename, '.php');
        })->mapWithKeys(function ($filename) {
            $resource = substr($filename, 0, -4);
            return [str_plural(lcfirst(substr($filename, 0, -4))) => '\\App\\Thrust\\' . $resource];
        });
    }

    /**
     * @param $resourceName
     * @return Resource
     */
    public function make($resourceName)
    {
        if ($resourceName instanceof Resource) {
            return $resourceName;
        }
        $type  = lcfirst(str_plural($resourceName));
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
        return lcfirst(str_plural($name)) ;
    }
}
