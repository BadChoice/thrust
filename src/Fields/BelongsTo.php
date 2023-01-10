<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\ResourceManager;

class BelongsTo extends Relationship
{
    protected $allowNull = false;
    protected $inlineCreation = false;

    public function allowNull($allowNull = true) : self
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    public function inlineCreation($inlineCreation = true) : self
    {
        $this->inlineCreation = $inlineCreation;
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
        $possibleRelations = $this->relatedQuery($object, true)->pluck($this->relationDisplayField, $this->getOwnerKey($object));
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
                'field'         => $this->databaseField($object),
                'relationship'  => $this->field,
                'value'         => $this->getValueId($object),
                'name'          => $this->getRelationName($object),
                'id'            => $object->id,
                'allowNull'     => $this->allowNull,
                'inline'        => $inline,
                'description'   => $this->getDescription(),
                'inlineCreation' => $this->inlineCreation,
                'inlineCreationData' => $this->inlineCreationData($object)
            ]);
        }
        return view('thrust::fields.select', [
            'title'         => $this->getTitle(),
            'field'         => $this->databaseField($object),
            'searchable'    => $this->searchable,
            'value'         => $this->getValueId($object),
            'options'       => $this->getOptions($object),
            'inline'        => $inline,
            'description'   => $this->getDescription(),
            'inlineCreation' => $this->inlineCreation,
            'inlineCreationData' => $this->inlineCreationData($object)
        ]);
    }

    public function getValueId($object){
        return $object->{$this->field}->{$this->getOwnerKey($object)} ?? null;
    }

    protected function inlineCreationData($object){
        return [
            "relationResource"     => collect(explode("\\", get_Class($this->getRelation($object)->getRelated())))->last(),
            "relationDisplayField" => $this->relationDisplayField,
        ];
    }

    public function sorted($field = 'order', $ascending = 'asc')
    {
        $this->relatedScope(function($query) use ($field, $ascending) {
            return $query->orderBy($field,$ascending);
        });
        return $this;
    }
}
