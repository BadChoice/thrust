<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\ResourceManager;

class HasMany extends Relationship
{
    public $showInEdit     = false;
    public $link           = null;
    public $resourceName   = null;
    public $icon           = null;
    public $useTitle;

    public static function make($dbField, $title = null)
    {
        $field = parent::make($dbField, $title);
        $field->resourceName($dbField);
        return $field;
    }

    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function useTitle($useTitle = true)
    {
        $this->useTitle = $useTitle;
        return $this;
    }

    public function resourceName($resourceName)
    {
        $this->resourceName = $resourceName;
        return $this;
    }

    public function displayInIndex($object)
    {
        return view('thrust::fields.hasMany', [
            'value'         => $this->getIndexText($object),
            'withLink'      => $this->withLink,
            'link'          => $this->link ? str_replace('{id}', $object->id, $this->link) : null,
            'relationship'  => $this->field,
            'id'            => $object->id,
            'icon'          => $this->icon,
            'resourceName'  => app(ResourceManager::class)->resourceNameFromModel($object),
        ]);
    }

    //TODO :Reuse the one in belongsToMany
    public function getTitle()
    {
        return $this->title ?? trans_choice(config('thrust.translationsPrefix') . str_singular($this->field), 2);
    }

    public function getIndexText($object)
    {
        if ($this->useTitle) {
            return $this->getTitle() . ' (' . $this->getRelation($object)->count() . ')';
        }
        return $object->{$this->field}->pluck($this->relationDisplayField)->implode(', ');
    }

    public function displayInEdit($object, $inline = false)
    {
    }
}
