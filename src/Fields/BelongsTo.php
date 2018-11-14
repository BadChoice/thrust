<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\ResourceManager;

class BelongsTo extends Relationship
{
    protected $allowNull = false;

    public function allowNull($allowNull = true)
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    public function displayInIndex($object)
    {
        $relation       = $this->getValue($object);
        $relationName   = $this->getRelationName($object);
        if (! $this->withLink) {
            return $relationName;
        }
        if (! $relation) {
            return '--';
        }
        return view('thrust::fields.link', [
            'url'   => route('thrust.edit', [app(ResourceManager::class)->resourceNameFromModel($relation), $this->getValue($object)->id]),
            'value' => $relationName,
            'class' => 'showPopup'
        ]);
    }

    public function getOptions($object)
    {
        $possibleRelations = $this->relatedQuery($object, true)->pluck($this->relationDisplayField, $this->getRelation($object)->getOwnerKey());
        if ($this->allowNull) {
            return $possibleRelations->prepend('--', '')->toArray();
        }
        return $possibleRelations;
    }

    public function displayInEdit($object, $inline = false)
    {
        if ($this->ajaxSearch) {
            return view('thrust::fields.selectAjax', [
                'resourceName'  => app(ResourceManager::class)->resourceNameFromModel(get_class($object)),
                'title'         => $this->getTitle(),
                'field'         => $this->getRelation($object)->getForeignKey(),
                'relationship'  => $this->field,
                'value'         => $object->{$this->field}->id ?? null,
                'name'          => $this->getRelationName($object),
                'id'            => $object->id,
                'allowNull'     => $this->allowNull,
                'inline'        => $inline,
            ]);
        }
        return view('thrust::fields.select', [
            'title'         => $this->getTitle(),
            'field'         => $this->getRelation($object)->getForeignKey(),
            'searchable'    => $this->searchable,
            'value'         => $object->{$this->field}->id ?? null,
            'options'       => $this->getOptions($object),
            'inline'        => $inline,
        ]);
    }
}
