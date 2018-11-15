<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Contracts\Prunable;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class Image extends File implements Prunable
{
    protected $classes          = 'gravatar';
    protected $resizedPrefix    = 'resized_';
    protected $gravatarField;
    protected $gravatarDefault;

    protected $maxHeight = 400;
    protected $maxWidth  = 400;

    protected $maxFileSize = 1024; // 1 MB

    public function gravatar($field = 'email', $default = null)
    {
        $this->gravatarField   = $field;
        $this->gravatarDefault = $default;
        return $this;
    }

    public function maxSize($width, $height)
    {
        $this->maxWidth  = $width;
        $this->maxHeight = $height;
        return $this;
    }

    public function displayInIndex($object)
    {
        return view('thrust::fields.image', [
            'title'         => $this->getTitle(),
            'path'          => $this->displayPath($object, $this->resizedPrefix),
            'gravatar'      => $this->gravatarField ? Gravatar::make($this->gravatarField)->withDefault($this->gravatarDefault)->getImageTag($object) : null,
            'classes'       => $this->classes,
            'style'         => $this->indexStyle,
            'exists'        => $this->exists($object),
            'resourceName'  => app(ResourceManager::class)->resourceNameFromModel($object),
            'id'            => $object->id,
            'field'         => $this->field,
            'inline'        => true,
            'description'   => $this->getDescription(),
            'withLink'      => $this->withLink
        ])->render();
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.image', [
            'title'         => $this->getTitle(),
            'path'          => $this->displayPath($object),
            'gravatar'      => $this->gravatarField ? Gravatar::make($this->gravatarField)->getImageTag($object) : null,
            'classes'       => $this->editClasses,
            'exists'        => $this->exists($object),
            'style'         => $inline ? $this->indexStyle : $this->editStyle,
            'resourceName'  => app(ResourceManager::class)->resourceNameFromModel($object),
            'id'            => $object->id,
            'field'         => $this->field,
            'inline'        => $inline,
            'description'   => $this->getDescription(),
            'withLink'      => ! $inline && $this->withLink
        ])->render();
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
        Storage::put($this->getPath() . $filename, (string)$image->encode('png'));
        Storage::put($this->getPath() . "{$this->resizedPrefix}{$filename}", (string)$image->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('png'));
        $this->updateField($object, $filename);
    }

    protected function deleteFile($object)
    {
        parent::deleteFile($object);
        Storage::delete($this->filePath($object, $this->resizedPrefix));
    }
}
