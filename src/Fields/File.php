<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Contracts\Prunable;
use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Support\Facades\Storage;

class File extends Field implements Prunable
{
    public $rowClass = 'fw3';
    protected $basePath;
    protected $basePathBindings = [];
    protected $displayCallback;
    public $prunable            = true;
    public $showInEdit          = false;
    public $editClasses         = 'br1';
    public $indexStyle          = 'height:30px; width:30px; object-fit: cover;';
    public $editStyle           = 'height:150px; width:300px; object-fit: cover;';
    public $withLink            = true;
    protected $filename         = null;
    public $onlyUpload          = false;

    protected $maxFileSize = 10240; // 10 MB

    public function classes($classes)
    {
        $this->classes = $classes;
        return $this;
    }

    public function withLink($withLink = true)
    {
        $this->withLink = $withLink;
        return $this;
    }

    public function path($path, $bindings = [])
    {
        $this->basePath         = $path . '/';
        $this->basePathBindings = $bindings;
        return $this;
    }

    public function maxFileSize($fileSize)
    {
        $this->maxFileSize = $fileSize;
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
        return view('thrust::fields.file', [
            'title'         => $this->getTitle(),
            'path'          => $this->displayPath($object),
            'classes'       => $this->classes,
            'style'         => $this->indexStyle,
            'resourceName'  => Thrust::resourceNameFromModel($object),
            'id'            => $object->id,
            'field'         => $this->field,
            'inline'        => true,
            'description'   => $this->getDescription(),
            'withLink'      => $this->withLink
        ])->render();
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.file', [
            'title'         => $this->getTitle(),
            'path'          => $this->displayPath($object),
            'exists'        => $this->exists($object),
            'classes'       => $this->editClasses,
            'style'         => $inline ? $this->indexStyle : $this->editStyle,
            'resourceName'  => Thrust::resourceNameFromModel($object),
            'id'            => $object->id,
            'field'         => $this->field,
            'inline'        => $inline,
            'description'   => $this->getDescription(),
            'withLink'      => ! $inline && $this->withLink
        ])->render();
    }

    public function displayPath($object, $prefix = '')
    {
        if (! $this->onlyUpload && ! $this->getValue($object)) {
            return null;
        }
        if ($this->displayCallback) {
            return call_user_func($this->displayCallback, $object, $prefix);
        }
        return $this->filePath($object, $prefix);
    }

    public function onlyUpload($value)
    {
        $this->onlyUpload = $value;
        return $this;
    }

    protected function filePath($object, $namePrefix = '')
    {
        if (! $this->onlyUpload && ! $this->getValue($object)) {
            return null;
        }

        if ($this->onlyUpload) {
            return $this->getPath() . $this->filename;
        }

        return $this->getPath() . $namePrefix . $this->getValue($object);
    }

    protected function getPath()
    {
        if (! $this->basePath) {
            return storage_path('thrust');
        }
        // TODO: Use the bindings!
        return str_replace('{user}', auth()->user()->username, $this->basePath);
    }

    public function store($object, $file)
    {
        $this->delete($object, false);
        $filename   = str_random(10) . "." . $file->extension();
        Storage::putFileAs($this->getPath(), $file, $this->filename ?? $filename);
        $this->updateField($object, $filename);
    }

    protected function updateField($object, $value)
    {
        if ($this->onlyUpload) {
            return;
        }
        $object->update([$this->field => $value]);
    }

    public function exists($object)
    {
        if (! $this->filename && ! $object->{$this->field}) return false;
        return Storage::exists($this->getPath(). ($this->filename ?? $object->{$this->field}));
    }

    public function delete($object, $updateObject = false)
    {
        if (! $this->onlyUpload && ! $this->getValue($object)) {
            return;
        }
        $this->deleteFile($object);
        if ($updateObject) {
            $this->updateField($object, null);
        }
    }

    protected function deleteFile($object)
    {
        Storage::delete($this->filePath($object));
    }

    public function prune($object)
    {
        $this->delete($object);
    }
}
