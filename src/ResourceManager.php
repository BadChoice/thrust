<?php

namespace BadChoice\Thrust;

use Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
        $this->resources = collect($this->scandirRecursive($folder))->filter(function ($filename) {
            return str_contains($filename, '.php');
        })->map(function ($path) use ($folder) {
            $filePath = str_replace($folder.DIRECTORY_SEPARATOR,"",$path);
            $filePath = str_replace("/","\\",$filePath);
            $resourcePath = substr($filePath, 0, -4);
            return '\\App\\Thrust\\' . $resourcePath;
        });
    }

    function scandirRecursive($dir,$results = []){
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isDir()) continue;
            $path = $file->getPathname();
            $results[str_plural(lcfirst(substr(basename($path), 0, -4)))] = $path;
        }
        return $results;
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
