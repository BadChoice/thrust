<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Contracts\Prunable;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class Image extends Field implements Prunable
{
    public $rowClass = 'fw3';
    protected $basePath;
    protected $basePathBindings = [];
    protected $displayCallback;
    protected $classes          = 'gravatar';
    protected $resizedPrefix    = 'resized_';
    protected $gravatarField;
    public    $prunable         = true;

    protected $maxHeight = 400;
    protected $maxWidth = 400;

    public function gravatar($field = 'email')
    {
        $this->gravatarField = $field;
        return $this;
    }

    public function classes($classes)
    {
        $this->classes = $classes;
        return $this;
    }

    public function path($path, $bindings = [])
    {
        $this->basePath         = $path . '/';
        $this->basePathBindings = $bindings;
        return $this;
    }

    public function maxSize($width, $height){
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }

    public function prunable($prunable = true)
    {
        $this->prunable = $prunable;
        return $this;
    }

    /**
     * Set a callback that will be used to convert the filename to a full url
     * The callback can also be a class with the __invoke so it can be reused without wiring anything into the resource
     * @param $displayCallback
     * @return $this
     */
    public function display($displayCallback)
    {
        $this->displayCallback = $displayCallback;
        return $this;
    }

    public function displayInIndex($object)
    {
        return view('thrust::fields.image',[
            'path'          => $this->displayPath($object, $this->resizedPrefix),
            'gravatar'      => $this->gravatarField ? Gravatar::make($this->gravatarField)->getImageTag($object) : null,
            'classes'       => $this->classes,
            'resourceName'  => app(ResourceManager::class)->resourceNameFromModel($object),
            'id'            => $object->id,
            'field'         => $this->field,
        ])->render();
    }

    public function displayInEdit($object, $inline = false)
    {

    }

    public function displayPath($object, $prefix = '')
    {
        if (! $this->getValue($object)) return null;
        if ($this->displayCallback){
            return call_user_func($this->displayCallback, $object, $prefix);
        }
        return $this->filePath($object, $prefix);
    }

    protected function filePath($object, $namePrefix = '')
    {
        if (! $this->getValue($object)) return null;
        return $this->getPath() . $namePrefix . $this->getValue($object);
    }

    protected function getPath()
    {
        if (! $this->basePath) return storage_path('thrust');
        return str_replace("{user}", auth()->user()->username, $this->basePath);
    }

    public function store($object, $file)
    {
        $this->delete($object, false);
        $image      = InterventionImage::make($file);

        $image->resize($this->maxWidth, $this->maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $filename   = str_random(10) . '.png';
        Storage::put($this->getPath() . $filename,                           (string)$image->encode('png'));
        Storage::put($this->getPath() . "{$this->resizedPrefix}{$filename}", (string)$image->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('png'));
        $object->update([$this->field => $filename]);
    }

    public function delete($object, $updateObject = false)
    {
        if (! $this->getValue($object)) return;
        Storage::delete($this->filePath($object, $this->resizedPrefix));
        Storage::delete($this->filePath($object));
        if ($updateObject) {
            $object->update([$this->field => null]);
        }
    }

    public function prune($object)
    {
        $this->delete($object);
    }


}